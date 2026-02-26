<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\TindakanSiswa;

class AISuggestionService
{
    /**
     * Get AI-based suggestions based on student's violation history
     * 
     * @param int $siswaId
     * @return array
     */
    public function getSuggestions($siswaId)
    {
        // Get total points
        $totalPoin = DB::table('pelanggarans')
            ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->where('pelanggarans.id_siswa', $siswaId)
            ->sum('kategori_pelanggarans.poin');

        // Get violation count
        $totalPelanggaran = DB::table('pelanggarans')
            ->where('id_siswa', $siswaId)
            ->count();

        // Get category breakdown
        $kategoriStats = DB::table('pelanggarans')
            ->select(
                'kategori_pelanggarans.nama as kategori',
                DB::raw('COUNT(pelanggarans.id) as jumlah'),
                DB::raw('SUM(kategori_pelanggarans.poin) as total_poin')
            )
            ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->where('pelanggarans.id_siswa', $siswaId)
            ->groupBy('kategori_pelanggarans.nama')
            ->get();

        // Get counts per category
        $countRingan = $kategoriStats->where('kategori', 'Ringan')->sum('jumlah');
        $countSedang = $kategoriStats->where('kategori', 'Sedang')->sum('jumlah');
        $countBerat = $kategoriStats->where('kategori', 'Berat')->sum('jumlah');

        // Get recent violations (last 30 days)
        $recentPelanggaran = DB::table('pelanggarans')
            ->where('id_siswa', $siswaId)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->count();

        // Get action history
        $latestAction = TindakanSiswa::getLatestAction($siswaId);
        $actionHistory = TindakanSiswa::getActionsForSiswa($siswaId);
        $hasUnresolvedAction = TindakanSiswa::hasUnresolvedAction($siswaId);

        // Determine level and generate suggestions
        $level = $this->determineLevel($totalPoin, $countBerat, $recentPelanggaran, $latestAction, $hasUnresolvedAction);
        $suggestions = $this->generateSuggestions($totalPoin, $totalPelanggaran, $countRingan, $countSedang, $countBerat, $recentPelanggaran, $latestAction, $actionHistory);
        
        return [
            'total_poin' => $totalPoin,
            'total_pelanggaran' => $totalPelanggaran,
            'kategori_stats' => [
                'ringan' => $countRingan,
                'sedang' => $countSedang,
                'berat' => $countBerat
            ],
            'recent_violations' => $recentPelanggaran,
            'level' => $level,
            'suggestions' => $suggestions,
            'priority' => $this->getPriority($totalPoin, $countBerat),
            'action_history' => [
                'has_actions' => $actionHistory->count() > 0,
                'latest_action' => $latestAction ? [
                    'jenis_tindakan' => $latestAction->jenis_tindakan,
                    'hasil_tindakan' => $latestAction->hasil_tindakan,
                    'tanggal_tindakan' => $latestAction->tanggal_tindakan,
                    'catatan_hasil' => $latestAction->catatan_hasil
                ] : null,
                'has_unresolved' => $hasUnresolvedAction,
                'total_actions' => $actionHistory->count()
            ]
        ];
    }

    /**
     * Determine the intervention level based on points and violation patterns
     */
    private function determineLevel($totalPoin, $countBerat, $recentPelanggaran, $latestAction = null, $hasUnresolvedAction = false)
    {
        // Boost level if there are unresolved actions
        $levelBoost = $hasUnresolvedAction ? 1 : 0;

        // High priority: banyak pelanggaran berat atau point sangat tinggi
        if ($totalPoin >= 76 || $countBerat >= 3) {
            return [
                'name' => 'Tingkat Tinggi',
                'color' => 'rose',
                'icon' => 'alert',
                'description' => 'Memerlukan tindakan tegas dan koordinasi intensif'
            ];
        }

        // Medium-high: point sedang tinggi atau pelanggaran berat
        if ($totalPoin >= 51 || $countBerat >= 2) {
            return [
                'name' => 'Tingkat Menengah Tinggi',
                'color' => 'orange',
                'icon' => 'warning',
                'description' => 'Memerlukan koordinasi dengan orang tua dan pihak sekolah'
            ];
        }

        // Medium: point menengah atau pelanggaran sedang
        if ($totalPoin >= 31 || $recentPelanggaran >= 3) {
            return [
                'name' => 'Tingkat Menengah',
                'color' => 'amber',
                'icon' => 'info',
                'description' => 'Perlu perhatian khusus dan pengawasan'
            ];
        }

        // Low-medium: point rendah tapi ada pelanggaran
        if ($totalPoin >= 11 || $recentPelanggaran >= 2) {
            return [
                'name' => 'Tingkat Rendah',
                'color' => 'blue',
                'icon' => 'check',
                'description' => 'Pemberian peringatan dan pembinaan'
            ];
        }

        // Minimal: point sangat rendah
        return [
            'name' => 'Minimal',
            'color' => 'emerald',
            'icon' => 'check-circle',
            'description' => 'Pemantauan rutin dan dukungan positif'
        ];
    }

    /**
     * Generate contextual suggestions based on violation patterns and action history
     */
    private function generateSuggestions($totalPoin, $totalPelanggaran, $countRingan, $countSedang, $countBerat, $recentPelanggaran, $latestAction = null, $actionHistory = null)
    {
        $suggestions = [];

        // === TINGKAT POIN ===
        if ($totalPoin == 0) {
            $suggestions[] = [
                'type' => 'positive',
                'title' => 'Pertahankan Prestasi Baik',
                'description' => 'Siswa belum memiliki pelanggaran. Tetap berikan apresiasi atas perilakunya.',
                'action' => 'Berikan penghargaan atau pujian kepada siswa'
            ];
        } elseif ($totalPoin <= 10) {
            $suggestions[] = [
                'type' => 'light',
                'title' => 'Pemberian Peringatan Ringan',
                'description' => 'Siswa memiliki pelanggaran ringan. Berikan pembinaan secara personal.',
                'action' => 'Panggilan siswa untuk pembicaraan tertutup'
            ];
        } elseif ($totalPoin <= 30) {
            $suggestions[] = [
                'type' => 'medium',
                'title' => 'Surat Peringatan & Pemanggilan Orang Tua',
                'description' => 'Poin mencapai tingkatan yang memerlukan perhatian orang tua.',
                'action' => 'Terbitkan surat peringatan dan undang orang tua untuk pertemuan'
            ];
        } elseif ($totalPoin <= 50) {
            $suggestions[] = [
                'type' => 'high',
                'title' => 'Surat Peringatan Keras',
                'description' => 'Poin mencapai tingkatan serius. Diperlukan tindakan lebih tegas.',
                'action' => 'Surat peringatan keras + pertemuan dengan orang tua dan Wakasek'
            ];
        } elseif ($totalPoin <= 75) {
            $suggestions[] = [
                'type' => 'serious',
                'title' => 'Skorsing & Koordinasi Khusus',
                'description' => 'Siswa memerlukan penanganan khusus dari pihak sekolah.',
                'action' => 'Skorsing sementara + koordinasi dengan Kepala Sekolah'
            ];
        } else {
            $suggestions[] = [
                'type' => 'critical',
                'title' => 'Tindakan Tegas & MoU',
                'description' => 'Siswa mencapai tingkatan yang memerlukan tindakan maksimal.',
                'action' => 'Upacara pembentukan karakter + MoU dengan orang tua'
            ];
        }

        // === POLA PELANGGARAN ===
        
        // Banyak pelanggaran ringan
        if ($countRingan >= 3 && $countRingan > $countSedang && $countRingan > $countBerat) {
            $suggestions[] = [
                'type' => 'pattern',
                'title' => 'Pola Pelanggaran Ringan Berulang',
                'description' => "Siswa telah melakukan {$countRingan}x pelanggaran ringan. Ini menunjukkan kebiasaan yang perlu diubah.",
                'action' => 'Bimbing siswa untuk membangun kebiasaan baik dan disiplin diri'
            ];
        }

        // Banyak pelanggaran sedang
        if ($countSedang >= 2) {
            $suggestions[] = [
                'type' => 'pattern',
                'title' => 'Pelanggaran Sedang',
                'description' => "Siswa telah melakukan {$countSedang}x pelanggaran sedang. Memerlukan pengawasan ketat.",
                'action' => 'Buat perjanjian tertulis (PBK) dengan siswa dan orang tua'
            ];
        }

        // Ada pelanggaran berat
        if ($countBerat >= 1) {
            $suggestions[] = [
                'type' => 'critical',
                'title' => 'Pelanggaran Berat Terdeteksi',
                'description' => "Siswa memiliki {$countBerat}x pelanggaran berat. Memerlukan penanganan serius.",
                'action' => 'Koordinasi dengan Kepala Sekolah dan buat MoU dengan orang tua'
            ];
        }

        // Pelanggaran berat berulang
        if ($countBerat >= 2) {
            $suggestions[] = [
                'type' => 'critical',
                'title' => 'Pelanggaran Berat Berulang',
                'description' => 'Pelanggaran berat lebih dari 1x. Diperlukan tindakan segera.',
                'action' => 'Pertimbangkan skorsing atau pemindahan siswa ke sekolah lain'
            ];
        }

        // Pelanggaran recent (30 hari)
        if ($recentPelanggaran >= 3) {
            $suggestions[] = [
                'type' => 'pattern',
                'title' => 'Pelanggaran Bertambah Tinggi',
                'description' => "Dalam 30 hari terakhir, siswa telah melakukan {$recentPelanggaran}x pelanggaran. Kondisi memburuk.",
                'action' => 'Lakukan evaluasi mendalam dan buat rencana perbaikan bersama'
            ];
        }

        // === TINDAKHANDING KHUSUS ===
        
        // Kombinasi point tinggi + banyak pelanggaran
        if ($totalPoin >= 30 && $totalPelanggaran >= 5) {
            $suggestions[] = [
                'type' => 'special',
                'title' => 'Evaluasi Komprehensif',
                'description' => 'Siswa memiliki banyak pelanggaran dengan point tinggi. Memerlukan pendekatan holistik.',
                'action' => 'Libatkan BK, Wali Kelas, dan orang tua dalam rapat evaluasi'
            ];
        }

        // === ACTION HISTORY BASED SUGGESTIONS ===
        
        if ($latestAction) {
            // Previous action was not successful - suggest follow-up
            if ($latestAction->hasil_tindakan === 'Tidak Berhasil') {
                $suggestions[] = [
                    'type' => 'follow-up',
                    'title' => 'Tindakan Sebelumnya Tidak Berhasil',
                    'description' => "Tindakan '{$latestAction->jenis_tindakan}' pada {$latestAction->tanggal_tindakan} belum berhasil. Diperlukan evaluasi dan tindakan lanjutan.",
                    'action' => 'Lakukan tindakan lebih tegas atau berbeda. Pertimbangkan untuk melibatkan pihak lain (BK, Wakasek, Kepala Sekolah)'
                ];
            }
            
            // Previous action is still ongoing
            if ($latestAction->hasil_tindakan === 'Sedang Berlangsung') {
                $suggestions[] = [
                    'type' => 'follow-up',
                    'title' => 'Tindakan Masih Berlangsung',
                    'description' => "Tindakan '{$latestAction->jenis_tindakan}' masih dalam proses. Perlu pemantauan lanjutan.",
                    'action' => 'Pantau perkembangan dan evaluasi hasil tindakan secara berkala'
                ];
            }
            
            // Previous action was successful - suggest maintenance
            if ($latestAction->hasil_tindakan === 'Berhasil') {
                $suggestions[] = [
                    'type' => 'positive',
                    'title' => 'Tindakan Sebelumnya Berhasil',
                    'description' => "Tindakan '{$latestAction->jenis_tindakan}' pada {$latestAction->tanggal_tindakan} berhasil. Pertahankan koordinasi.",
                    'action' => 'Lanjutkan pemantauan dan berikan penguatan positif kepada siswa'
                ];
            }
            
            // Needs evaluation
            if ($latestAction->hasil_tindakan === 'Perlu Evaluasi') {
                $suggestions[] = [
                    'type' => 'follow-up',
                    'title' => 'Perlu Evaluasi Tindakan',
                    'description' => "Tindakan '{$latestAction->jenis_tindakan}' pada {$latestAction->tanggal_tindakan} memerlukan evaluasi.",
                    'action' => 'Lakukan evaluasi mendalam dengan semua pihak terkait'
                ];
            }
        }

        // If has many previous actions but still has violations
        if ($actionHistory && $actionHistory->count() >= 2 && $totalPoin > 0) {
            $successfulActions = $actionHistory->where('hasil_tindakan', 'Berhasil')->count();
            $failedActions = $actionHistory->where('hasil_tindakan', 'Tidak Berhasil')->count();
            
            if ($failedActions > 0 && $successfulActions === 0) {
                $suggestions[] = [
                    'type' => 'critical',
                    'title' => 'Semua Tindakan Sebelumnya Gagal',
                    'description' => "Siswa telah melakukan {$actionHistory->count()}x tindakan tetapi semuanya belum berhasil.",
                    'action' => 'Segera koordinasi dengan Kepala Sekolah untuk penanganan khusus'
                ];
            } elseif ($failedActions > $successfulActions) {
                $suggestions[] = [
                    'type' => 'follow-up',
                    'title' => 'Tindakan Sebelumnya Kurang Efektif',
                    'description' => "Dari {$actionHistory->count()} tindakan, {$failedActions} belum berhasil.",
                    'action' => 'Evaluasi ulang strategi penanganan. Pertimbangkan pendekatan berbeda'
                ];
            }
        }

        return $suggestions;
    }

    /**
     * Get priority level for UI display
     */
    private function getPriority($totalPoin, $countBerat)
    {
        if ($totalPoin >= 76 || $countBerat >= 3) return 'critical';
        if ($totalPoin >= 51 || $countBerat >= 2) return 'serious';
        if ($totalPoin >= 31 || $totalPoin >= 11) return 'medium';
        if ($totalPoin > 0) return 'low';
        return 'none';
    }

    /**
     * Check if student has violations with evidence photos
     */
    public function hasEvidencePhotos($siswaId)
    {
        return DB::table('pelanggarans')
            ->where('id_siswa', $siswaId)
            ->whereNotNull('bukti_foto')
            ->exists();
    }

    /**
     * Get violations with evidence photos for a student
     */
    public function getViolationsWithPhotos($siswaId)
    {
        return DB::table('pelanggarans')
            ->select(
                'pelanggarans.id',
                'pelanggarans.bukti_foto',
                'pelanggarans.created_at',
                'jenis_pelanggarans.nama as jenis_pelanggaran',
                'kategori_pelanggarans.nama as kategori',
                'kategori_pelanggarans.poin'
            )
            ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->where('pelanggarans.id_siswa', $siswaId)
            ->whereNotNull('pelanggarans.bukti_foto')
            ->orderBy('pelanggarans.created_at', 'desc')
            ->get();
    }
}

