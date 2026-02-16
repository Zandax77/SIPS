<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class LaporanExport implements FromCollection, WithHeadings, WithTitle
{
    protected $type;
    protected $params;

    public function __construct(string $type, array $params = [])
    {
        $this->type = $type;
        $this->params = $params;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        switch ($this->type) {
            case 'per-siswa':
                return $this->getPerSiswa();
            case 'per-kelas':
                return $this->getPerKelas();
            case 'per-periode':
                return $this->getPerPeriode();
            case 'siswa-tertinggi':
                return $this->getSiswaTertinggi();
            default:
                return collect([]);
        }
    }

    /**
     * Get data for Laporan per Siswa
     */
    private function getPerSiswa()
    {
        $kelas = $this->params['kelas'] ?? '';
        $search = $this->params['search'] ?? '';

        return DB::table('siswas')
            ->select(
                'siswas.nis',
                'siswas.name as nama_siswa',
                'siswas.kelas',
                DB::raw('COALESCE(SUM(kategori_pelanggarans.poin), 0) as total_poin'),
                DB::raw('COUNT(DISTINCT pelanggarans.id) as total_pelanggaran')
            )
            ->leftJoin('pelanggarans', 'siswas.id', '=', 'pelanggarans.id_siswa')
            ->leftJoin('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->leftJoin('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->when($kelas, function ($query) use ($kelas) {
                return $query->where('siswas.kelas', $kelas);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('siswas.name', 'like', "%{$search}%")
                      ->orWhere('siswas.nis', 'like', "%{$search}%");
                });
            })
            ->groupBy('siswas.id', 'siswas.nis', 'siswas.name', 'siswas.kelas')
            ->orderBy('total_poin', 'desc')
            ->get()
            ->map(function ($item, $key) {
                return [
                    'No' => $key + 1,
                    'NIS' => $item->nis,
                    'Nama Siswa' => $item->nama_siswa,
                    'Kelas' => $item->kelas,
                    'Total Poin' => $item->total_poin,
                    'Jml Pelanggaran' => $item->total_pelanggaran,
                ];
            });
    }

    /**
     * Get data for Rekap per Kelas
     */
    private function getPerKelas()
    {
        $kelas = $this->params['kelas'] ?? '';

        return DB::table('siswas')
            ->select(
                'siswas.kelas',
                DB::raw('COUNT(DISTINCT siswas.id) as total_siswa'),
                DB::raw('COUNT(DISTINCT pelanggarans.id) as total_pelanggaran'),
                DB::raw('COALESCE(SUM(kategori_pelanggarans.poin), 0) as total_poin'),
                DB::raw('COUNT(DISTINCT CASE WHEN kategori_pelanggarans.nama = "Ringan" THEN pelanggarans.id END) as pelanggaran_ringan'),
                DB::raw('COUNT(DISTINCT CASE WHEN kategori_pelanggarans.nama = "Sedang" THEN pelanggarans.id END) as pelanggaran_sedang'),
                DB::raw('COUNT(DISTINCT CASE WHEN kategori_pelanggarans.nama = "Berat" THEN pelanggarans.id END) as pelanggaran_berat')
            )
            ->leftJoin('pelanggarans', 'siswas.id', '=', 'pelanggarans.id_siswa')
            ->leftJoin('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->leftJoin('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->when($kelas, function ($query) use ($kelas) {
                return $query->where('siswas.kelas', $kelas);
            })
            ->groupBy('siswas.kelas')
            ->orderBy('siswas.kelas')
            ->get()
            ->map(function ($item, $key) {
                return [
                    'No' => $key + 1,
                    'Kelas' => $item->kelas,
                    'Total Siswa' => $item->total_siswa,
                    'Pelanggaran Ringan' => $item->pelanggaran_ringan,
                    'Pelanggaran Sedang' => $item->pelanggaran_sedang,
                    'Pelanggaran Berat' => $item->pelanggaran_berat,
                    'Total Pelanggaran' => $item->total_pelanggaran,
                    'Total Poin' => $item->total_poin,
                ];
            });
    }

    /**
     * Get data for Rekap per Periode
     */
    private function getPerPeriode()
    {
        $tanggalMulai = $this->params['tanggalMulai'] ?? date('Y-m-01');
        $tanggalAkhir = $this->params['tanggalAkhir'] ?? date('Y-m-t');
        $kelas = $this->params['kelas'] ?? '';

        return DB::table('pelanggarans')
            ->select(
                DB::raw('DATE(pelanggarans.created_at) as tanggal'),
                DB::raw('COUNT(DISTINCT pelanggarans.id_siswa) as siswa_pelaku'),
                DB::raw('COUNT(DISTINCT pelanggarans.id) as total_pelanggaran'),
                DB::raw('COALESCE(SUM(kategori_pelanggarans.poin), 0) as total_poin'),
                DB::raw('COUNT(DISTINCT CASE WHEN kategori_pelanggarans.nama = "Ringan" THEN pelanggarans.id END) as pelanggaran_ringan'),
                DB::raw('COUNT(DISTINCT CASE WHEN kategori_pelanggarans.nama = "Sedang" THEN pelanggarans.id END) as pelanggaran_sedang'),
                DB::raw('COUNT(DISTINCT CASE WHEN kategori_pelanggarans.nama = "Berat" THEN pelanggarans.id END) as pelanggaran_berat')
            )
            ->join('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->join('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->join('siswas', 'pelanggarans.id_siswa', '=', 'siswas.id')
            ->whereBetween('pelanggarans.created_at', [$tanggalMulai . ' 00:00:00', $tanggalAkhir . ' 23:59:59'])
            ->when($kelas, function ($query) use ($kelas) {
                return $query->where('siswas.kelas', $kelas);
            })
            ->groupBy(DB::raw('DATE(pelanggarans.created_at)'))
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($item, $key) {
                return [
                    'No' => $key + 1,
                    'Tanggal' => $item->tanggal,
                    'Siswa Pelaku' => $item->siswa_pelaku,
                    'Pelanggaran Ringan' => $item->pelanggaran_ringan,
                    'Pelanggaran Sedang' => $item->pelanggaran_sedang,
                    'Pelanggaran Berat' => $item->pelanggaran_berat,
                    'Total Pelanggaran' => $item->total_pelanggaran,
                    'Total Poin' => $item->total_poin,
                ];
            });
    }

    /**
     * Get data for Siswa Poin Tertinggi
     */
    private function getSiswaTertinggi()
    {
        $kelas = $this->params['kelas'] ?? '';
        $limit = $this->params['limit'] ?? 10;

        $siswa = DB::table('siswas')
            ->select(
                'siswas.nis',
                'siswas.name as nama_siswa',
                'siswas.kelas',
                DB::raw('COALESCE(SUM(kategori_pelanggarans.poin), 0) as total_poin'),
                DB::raw('COUNT(DISTINCT pelanggarans.id) as total_pelanggaran')
            )
            ->leftJoin('pelanggarans', 'siswas.id', '=', 'pelanggarans.id_siswa')
            ->leftJoin('jenis_pelanggarans', 'pelanggarans.id_jenis_pelanggaran', '=', 'jenis_pelanggarans.id')
            ->leftJoin('kategori_pelanggarans', 'jenis_pelanggarans.id_kategori_pelanggaran', '=', 'kategori_pelanggarans.id')
            ->when($kelas, function ($query) use ($kelas) {
                return $query->where('siswas.kelas', $kelas);
            })
            ->groupBy('siswas.id', 'siswas.nis', 'siswas.name', 'siswas.kelas')
            ->orderBy('total_poin', 'desc')
            ->orderBy('total_pelanggaran', 'desc')
            ->limit($limit)
            ->get();

        return $siswa->map(function ($item, $key) {
            return [
                'Rank' => $key + 1,
                'NIS' => $item->nis,
                'Nama Siswa' => $item->nama_siswa,
                'Kelas' => $item->kelas,
                'Total Poin' => $item->total_poin,
                'Jml Pelanggaran' => $item->total_pelanggaran,
            ];
        });
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        switch ($this->type) {
            case 'per-siswa':
                return ['No', 'NIS', 'Nama Siswa', 'Kelas', 'Total Poin', 'Jml Pelanggaran'];
            case 'per-kelas':
                return ['No', 'Kelas', 'Total Siswa', 'Pelanggaran Ringan', 'Pelanggaran Sedang', 'Pelanggaran Berat', 'Total Pelanggaran', 'Total Poin'];
            case 'per-periode':
                return ['No', 'Tanggal', 'Siswa Pelaku', 'Pelanggaran Ringan', 'Pelanggaran Sedang', 'Pelanggaran Berat', 'Total Pelanggaran', 'Total Poin'];
            case 'siswa-tertinggi':
                return ['Rank', 'NIS', 'Nama Siswa', 'Kelas', 'Total Poin', 'Jml Pelanggaran'];
            default:
                return [];
        }
    }

    /**
     * @return string
     */
    public function title(): string
    {
        switch ($this->type) {
            case 'per-siswa':
                return 'Laporan per Siswa';
            case 'per-kelas':
                return 'Rekap per Kelas';
            case 'per-periode':
                return 'Rekap per Periode';
            case 'siswa-tertinggi':
                return 'Siswa Poin Tertinggi';
            default:
                return 'Laporan';
        }
    }
}

