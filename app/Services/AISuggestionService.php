<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AISuggestionService
{
    /**
     * Generate AI-powered suggestions based on student violation history
     * Considers: nama pelanggaran, jenis pelanggaran, deskripsi, dan jumlah pelanggaran serupa
     *
     * @param int $siswaId Student ID
     * @return array Analysis results with recommendations
     */
    public function analyzeViolationHistory($siswaId)
    {
        // Get all violations for this student with full details
        $pelanggaranDetail = DB::table('pelanggarans')
            ->select(
                'pelanggarans.id',
                'pelanggarans.created_at',
                'pelanggarans.deskripsi',
                'jenis_pelanggarans.nama as nama_pelanggaran',
                'jenis_pelanggarans.deskripsi as deskripsi_jenis',
                'kategori_pelanggarans.nama as kategori',
                'kategori_pelanggarans.poin'
            )
            ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->where('pelanggarans.id_siswa', $siswaId)
            ->orderBy('pelanggarans.created_at', 'desc')
            ->get();

        // Calculate basic statistics
        $totalPoin = $pelanggaranDetail->sum('poin');
        $totalPelanggaran = $pelanggaranDetail->count();

        // Category breakdown
        $kategoriStats = $pelanggaranDetail->groupBy('kategori')->map(function ($items) {
            return [
                'jumlah' => $items->count(),
                'poin' => $items->sum('poin')
            ];
        });

        $ringanCount = $kategoriStats['Ringan']['jumlah'] ?? 0;
        $sedangCount = $kategoriStats['Sedang']['jumlah'] ?? 0;
        $beratCount = $kategoriStats['Berat']['jumlah'] ?? 0;

        // ========== BARU: Analisis Nama Pelanggaran & Pelanggaran Serupa ==========

        // Hitung frekuensi setiap nama pelanggaran
        $pelanggaranCounts = $pelanggaranDetail->groupBy('nama_pelanggaran')->map(function ($items) {
            return [
                'jumlah' => $items->count(),
                'poin' => $items->sum('poin'),
                'kategori' => $items->first()->kategori,
                'deskripsi' => $items->first()->deskripsi_jenis,
                'latest_date' => $items->max('created_at')
            ];
        });

        // Deteksi pelanggaran berulang (lebih dari 1x)
        $repeatedViolations = $pelanggaranCounts->filter(function ($data, $nama) {
            return $data['jumlah'] > 1;
        })->sortByDesc('jumlah');

        // Dapatkan pelanggaran yang paling sering terjadi
        $mostFrequentViolation = $pelanggaranCounts->sortByDesc('jumlah')->keys()->first() ?? null;
        $mostFrequentCount = $mostFrequentViolation ? $pelanggaranCounts[$mostFrequentViolation]['jumlah'] : 0;

        // ========== BARU: Analisis Deskripsi Pelanggaran ==========

        // Ekstrak kata kunci dari deskripsi
        $descriptionKeywords = $this->analyzeDescriptionKeywords($pelanggaranDetail);

        // ========== Analisis pola waktu ==========

        // Analyze recency patterns (last 30 days)
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $recentViolations = $pelanggaranDetail->filter(function ($item) use ($thirtyDaysAgo) {
            return Carbon::parse($item->created_at)->gte($thirtyDaysAgo);
        });
        $recentCount = $recentViolations->count();

        // Analyze trend (comparing last 30 days vs previous 30 days)
        $sixtyDaysAgo = Carbon::now()->subDays(60);
        $previousPeriodViolations = $pelanggaranDetail->filter(function ($item) use ($thirtyDaysAgo, $sixtyDaysAgo) {
            $date = Carbon::parse($item->created_at);
            return $date->gte($sixtyDaysAgo) && $date->lt($thirtyDaysAgo);
        });
        $previousCount = $previousPeriodViolations->count();

        // Calculate escalation trend
        $trend = 'stabil';
        if ($recentCount > $previousCount && $previousCount > 0) {
            $trend = 'meningkat';
        } elseif ($recentCount < $previousCount && $recentCount > 0) {
            $trend = 'menurun';
        }

        // Determine main category concern
        $kategoriUtama = $this->determineMainConcern($kategoriStats);

        // Generate level recommendation dengan pertimbangan pelanggaran serupa
        $recommendation = $this->generateRecommendation($totalPoin, $kategoriStats, $recentCount, $trend, $repeatedViolations->count());

        // Generate specific action items
        $actionItems = $this->generateActionItems($pelanggaranDetail, $kategoriStats, $totalPoin, $repeatedViolations, $mostFrequentViolation);

        // Generate contextual analysis
        $analysis = $this->generateAnalysis($pelanggaranDetail, $kategoriStats, $recentCount, $trend, $totalPoin, $pelanggaranCounts, $repeatedViolations, $descriptionKeywords);

        return [
            'total_poin' => $totalPoin,
            'total_pelanggaran' => $totalPelanggaran,
            'kategori_stats' => [
                'ringan' => $ringanCount,
                'sedang' => $sedangCount,
                'berat' => $beratCount
            ],
            'recent_count' => $recentCount,
            'trend' => $trend,
            'kategori_utama' => $kategoriUtama,
            'recommendation' => $recommendation,
            'action_items' => $actionItems,
            'analysis' => $analysis,
            'priority_level' => $this->calculatePriorityLevel($totalPoin, $beratCount, $recentCount, $trend, $repeatedViolations->count()),

            // ========== DATA BARU: Untuk display di view ==========
            'violation_analysis' => [
                'nama_pelanggaran_terbanyak' => $mostFrequentViolation,
                'jumlah_terbanyak' => $mostFrequentCount,
                'pelanggaran_berulang' => $repeatedViolations->count() > 0,
                'detail_pelanggaran_berulang' => $repeatedViolations->toArray(),
                'kata_kunci_deskripsi' => $descriptionKeywords,
                'semua_pelanggaran' => $pelanggaranCounts->toArray()
            ]
        ];
    }

    /**
     * Analisis kata kunci dari deskripsi pelanggaran
     */
    private function analyzeDescriptionKeywords($pelanggaranDetail)
    {
        $allDescriptions = '';

        foreach ($pelanggaranDetail as $p) {
            // Gabungkan deskripsi jenis dan deskripsi individual
            $allDescriptions .= ' ' . ($p->deskripsi ?? '') . ' ' . ($p->deskripsi_jenis ?? '');
        }

        // Kata kunci yang sering muncul dalam konteks pelanggaran
        $keywords = [
            'terlambat' => 0,
            'bolos' => 0,
            'merokok' => 0,
            'HP' => 0,
            'handphone' => 0,
            'seragam' => 0,
            'rambut' => 0,
            'bullying' => 0,
            'makan' => 0,
            'kendaraan' => 0,
            'motor' => 0,
            'parkir' => 0,
            'kelas' => 0,
            'ujian' => 0,
            'menyontek' => 0,
            'larang' => 0,
            'berbaju' => 0,
            'kaos' => 0,
            'celana' => 0
        ];

        $allDescriptions = strtolower($allDescriptions);

        foreach ($keywords as $keyword => &$count) {
            $count = substr_count($allDescriptions, strtolower($keyword));
        }

        // Filter kata kunci yang muncul
        return array_filter($keywords, function ($count) {
            return $count > 0;
        });
    }

    /**
     * Determine the main concern category
     */
    private function determineMainConcern($kategoriStats)
    {
        $maxCount = 0;
        $mainConcern = 'Tidak ada';

        foreach ($kategoriStats as $kategori => $data) {
            if ($data['jumlah'] > $maxCount) {
                $maxCount = $data['jumlah'];
                $mainConcern = $kategori;
            }
        }

        return $mainConcern;
    }

    /**
     * Generate recommendation based on points, patterns, and repeated violations
     */
    private function generateRecommendation($totalPoin, $kategoriStats, $recentCount, $trend, $repeatedCount)
    {
        $beratPoin = $kategoriStats['Berat']['poin'] ?? 0;

        // Base recommendation on total points
        if ($totalPoin == 0) {
            return [
                'level' => 'baik',
                'title' => 'Siswa Bersikap Baik',
                'description' => 'Siswa tidak memiliki pelanggaran yang tercatat. Tetap lakukan pemantauan rutin.',
                'icon' => 'check-circle',
                'color' => 'emerald'
            ];
        } elseif ($totalPoin <= 5) {
            // Jika ada pelanggaran berulang, naikkan level
            if ($repeatedCount > 0) {
                return [
                    'level' => 'pembinaan',
                    'title' => 'Perlu Pembinaan - Pelanggaran Berulang',
                    'description' => 'Siswa telah melakukan pelanggaran yang sama lebih dari sekali. Diperlukan pendekatan intensif untuk mencegah pengulangan.',
                    'icon' => 'user',
                    'color' => 'amber'
                ];
            }
            return [
                'level' => 'peringatan_ringan',
                'title' => 'Peringatan Ringan',
                'description' => 'Poin pelanggaran masih rendah. Berikan pengarahan untuk mencegah pelanggaran lanjutan.',
                'icon' => 'info',
                'color' => 'blue'
            ];
        } elseif ($totalPoin <= 15) {
            // Jika ada pelanggaran berulang, naikkan level ke surat putih
            if ($repeatedCount > 1) {
                return [
                    'level' => 'surat_putih',
                    'title' => 'Surat Putih (Peringatan) - Pola Berulang',
                    'description' => 'Siswa menunjukkan pola pelanggaran berulang. Segera buat surat peringatan kepada orang tua.',
                    'icon' => 'mail',
                    'color' => 'orange'
                ];
            }
            return [
                'level' => 'pembinaan',
                'title' => 'Perlu Pembinaan Intensif',
                'description' => 'Siswa memerlukan perhatian khusus. Jadwalkan sesi pendampingan dengan WALAS.',
                'icon' => 'user',
                'color' => 'amber'
            ];
        } elseif ($totalPoin <= 25) {
            return [
                'level' => 'surat_putih',
                'title' => 'Surat Putih (Peringatan)',
                'description' => 'Poin已达到 batas Surat Putih. Segera buat surat peringatan kepada orang tua.',
                'icon' => 'mail',
                'color' => 'orange'
            ];
        } elseif ($totalPoin <= 50) {
            return [
                'level' => 'surat_merah',
                'title' => 'Surat Merah (SP3)',
                'description' => 'Pelanggaran telah mencapai batas serius. Perlu koordinasi intensif dengan orang tua dan BK.',
                'icon' => 'alert-triangle',
                'color' => 'red'
            ];
        } else {
            return [
                'level' => 'ptos',
                'title' => 'Proses PTOS',
                'description' => 'Siswa telah mencapai batas maksimal. Persiapkan proses PTOS sesuai prosedur.',
                'icon' => 'x-circle',
                'color' => 'rose'
            ];
        }
    }

    /**
     * Calculate priority level (1-5) dengan pertimbangan pelanggaran berulang
     */
    private function calculatePriorityLevel($totalPoin, $beratCount, $recentCount, $trend, $repeatedCount)
    {
        $level = 1;

        // Base level from points
        if ($totalPoin > 50) $level = 5;
        elseif ($totalPoin > 25) $level = 4;
        elseif ($totalPoin > 15) $level = 3;
        elseif ($totalPoin > 5) $level = 2;

        // Escalate if there are berat violations
        if ($beratCount >= 3) {
            $level = min(5, $level + 1);
        }

        // Escalate if trend is increasing
        if ($trend === 'meningkat') {
            $level = min(5, $level + 1);
        }

        // Escalate if recent violations are frequent
        if ($recentCount >= 3) {
            $level = min(5, $level + 1);
        }

        // BARU: Tingkatkan level jika ada pelanggaran berulang
        if ($repeatedCount >= 2) {
            $level = min(5, $level + 1);
        }

        return $level;
    }

    /**
     * Generate specific action items based on violation patterns termasuk pelanggaran serupa
     */
    private function generateActionItems($pelanggaranDetail, $kategoriStats, $totalPoin, $repeatedViolations, $mostFrequentViolation)
    {
        $actionItems = [];

        // Get most common violation types
        $jenisCounts = $pelanggaranDetail->groupBy('nama_pelanggaran')->map(function ($items) {
            return $items->count();
        });

        $mostCommonViolation = $jenisCounts->sortDesc()->keys()->first() ?? null;

        // Action based on total points
        if ($totalPoin > 0 && $totalPoin <= 5) {
            $actionItems[] = [
                'type' => 'pengahargaan',
                'title' => 'Omong baik dengan siswa',
                'description' => 'Lakukan pembicaraan ringan untuk memahami kondisi siswa.',
                'priority' => 'low'
            ];
            $actionItems[] = [
                'type' => 'pendataan',
                'title' => 'Catat dalam book bimbingan',
                'description' => 'Dokumentasikan pelanggaran sebagai bahan evaluasi.',
                'priority' => 'low'
            ];
        }

        if ($totalPoin > 5 && $totalPoin <= 15) {
            $actionItems[] = [
                'type' => 'panggilan',
                'title' => 'Panggil siswa untuk wawancara',
                'description' => 'Lakukan wawancara mendalam untuk memahami akar masalah.',
                'priority' => 'medium'
            ];
            $actionItems[] = [
                'type' => 'kordinasi',
                'title' => 'Koordinasi dengan Wali Kelas',
                'description' => 'Berikan laporan perkembangan kepada Wali Kelas.',
                'priority' => 'medium'
            ];
            if ($mostCommonViolation) {
                $actionItems[] = [
                    'type' => 'fokus',
                    'title' => 'Fokus pada: ' . $mostCommonViolation,
                    'description' => 'Pelanggaran ini paling sering dilakukan. Berikan edukasi spesifik.',
                    'priority' => 'medium'
                ];
            }
        }

        // BARU: Tindakan khusus untuk pelanggaran berulang
        if ($repeatedViolations->count() > 0) {
            $repeatedList = $repeatedViolations->keys()->take(3)->implode(', ');
            $actionItems[] = [
                'type' => 'peringatan_khusus',
                'title' => '🚨 Peringatan: Pola Pelanggaran Berulang',
                'description' => 'Siswa melakukan pelanggaran yang sama secara berulang: ' . $repeatedList,
                'priority' => 'high'
            ];

            $actionItems[] = [
                'type' => 'pembinaan_khusus',
                'title' => 'Program Pembinaan Khusus',
                'description' => 'Buat program pembinaan khusus untuk mengatasi pelanggaran berulang.',
                'priority' => 'high'
            ];
        }

        if ($totalPoin > 15 && $totalPoin <= 25) {
            $actionItems[] = [
                'type' => 'surat',
                'title' => 'Buat Surat Putih',
                'description' => 'Surat peringatan resmi kepada orang tua/wali.',
                'priority' => 'high'
            ];
            $actionItems[] = [
                'type' => 'pertemuan',
                'title' => 'Pertemuan dengan Orang Tua',
                'description' => 'Panggil orang tua untuk membahas pelanggaran siswa.',
                'priority' => 'high'
            ];
            $actionItems[] = [
                'type' => 'rencana',
                'title' => 'Buat Rencana Bimbingan',
                'description' => 'Susun program bimbingan individual bersama BK.',
                'priority' => 'high'
            ];
        }

        if ($totalPoin > 25 && $totalPoin <= 50) {
            $actionItems[] = [
                'type' => 'surat_merah',
                'title' => 'Buat Surat Merah (SP3)',
                'description' => 'Surat peringatan ketiga dan terakhir sebelum PTOS.',
                'priority' => 'high'
            ];
            $actionItems[] = [
                'type' => 'konferensi',
                'title' => 'Konferensi Kasus',
                'description' => 'Adakan konferensi kasus dengan semua pihak terkait.',
                'priority' => 'high'
            ];
            $actionItems[] = [
                'type' => 'evaluasi',
                'title' => 'Evaluasi Proses Bimbingan',
                'description' => 'Evaluasi efektivitas bimbingan yang telah dilakukan.',
                'priority' => 'high'
            ];
        }

        if ($totalPoin > 50) {
            $actionItems[] = [
                'type' => 'ptos',
                'title' => 'Mulai Proses PTOS',
                'description' => 'Persiapkan administrasi dan persyaratan PTOS.',
                'priority' => 'critical'
            ];
            $actionItems[] = [
                'type' => 'komite',
                'title' => 'Koordinasi dengan Komite Sekolah',
                'description' => 'Libatkan komite sekolah dalam proses penyelesaian.',
                'priority' => 'critical'
            ];
        }

        // Add specific action for berat violations
        if (($kategoriStats['Berat']['jumlah'] ?? 0) > 0) {
            array_unshift($actionItems, [
                'type' => 'prioritas',
                'title' => 'Prioritas: Pelanggaran Berat',
                'description' => 'Terdapat pelanggaran berat yang memerlukan penanganan khusus.',
                'priority' => 'high'
            ]);
        }

        // Add action for increasing trend
        if ($totalPoin > 0) {
            $actionItems[] = [
                'type' => 'pencegahan',
                'title' => 'Pantau Perkembangan',
                'description' => 'Lakukan monitoring mingguan untuk mencegah eskalasi.',
                'priority' => 'medium'
            ];
        }

        return $actionItems;
    }

    /**
     * Generate contextual analysis text dengan pertimbangan pelanggaran serupa
     */
    private function generateAnalysis($pelanggaranDetail, $kategoriStats, $recentCount, $trend, $totalPoin, $pelanggaranCounts, $repeatedViolations, $descriptionKeywords)
    {
        $analysisParts = [];

        // Total summary
        if ($totalPoin == 0) {
            $analysisParts[] = "Siswa tidak memiliki pelanggaran tercatat dalam sistem.";
        } else {
            $analysisParts[] = "Siswa memiliki total {$totalPoin} poin pelanggaran dari {$pelanggaranDetail->count()} pelanggaran.";

            // Category breakdown
            $ringan = $kategoriStats['Ringan']['jumlah'] ?? 0;
            $sedang = $kategoriStats['Sedang']['jumlah'] ?? 0;
            $berat = $kategoriStats['Berat']['jumlah'] ?? 0;

            if ($ringan > 0 || $sedang > 0 || $berat > 0) {
                $breakdown = [];
                if ($ringan > 0) $breakdown[] = "{$ringan} Ringan";
                if ($sedang > 0) $breakdown[] = "{$sedang} Sedang";
                if ($berat > 0) $breakdown[] = "{$berat} Berat";
                $analysisParts[] = "Rincian: " . implode(", ", $breakdown);
            }

            // BARU: Info pelanggaran serupa/berulang
            if ($repeatedViolations->count() > 0) {
                $repeatedInfo = [];
                foreach ($repeatedViolations->take(3) as $nama => $data) {
                    $repeatedInfo[] = "{$nama} ({$data['jumlah']}x)";
                }
                $analysisParts[] = "⚠️ PELANGGARAN BERULANG: " . implode(", ", $repeatedInfo);
            }

            // BARU: Info jenis pelanggaran paling sering
            if ($pelanggaranCounts->count() > 0) {
                $maxViolation = $pelanggaranCounts->sortByDesc('jumlah')->keys()->first();
                $maxCount = $pelanggaranCounts[$maxViolation]['jumlah'];
                if ($maxCount > 0) {
                    $analysisParts[] = "Pelanggaran tertinggi: {$maxViolation} ({$maxCount}x)";
                }
            }

            // BARU: Info kata kunci dari deskripsi
            if (count($descriptionKeywords) > 0) {
                $keywordsList = array_keys($descriptionKeywords);
                $analysisParts[] = "Kata kunci dominan: " . implode(", ", array_slice($keywordsList, 0, 5));
            }
        }

        // Trend analysis
        if ($totalPoin > 0) {
            if ($trend === 'meningkat') {
                $analysisParts[] = "⚠️ Pola pelanggaran menunjukkan kecenderungan MENINGCAT dalam 30 hari terakhir. Perlu intervención segera.";
            } elseif ($trend === 'menurun') {
                $analysisParts[] = "✅ Tren pelanggaran sedang MENURUN. Tetap lakukan pemantauan.";
            } else {
                $analysisParts[] = "📊 Pola pelanggaran STABIL dalam periode terakhir.";
            }

            // Recent violations
            if ($recentCount > 0) {
                $analysisParts[] = "Dalam 30 hari terakhir, terdapat {$recentCount} pelanggaran baru.";
            }
        }

        // Severity warning
        $beratPoin = $kategoriStats['Berat']['poin'] ?? 0;
        if ($beratPoin > 0) {
            $analysisParts[] = "🚨 PERHATIAN: Terdapat {$beratPoin} poin dari pelanggaran BERAT yang memerlukan perhatian khusus.";
        }

        return implode(" ", $analysisParts);
    }
}

