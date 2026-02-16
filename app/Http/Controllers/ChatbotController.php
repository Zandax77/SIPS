<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\SIPSChatbotService;
use App\Models\Setting;

class ChatbotController extends Controller
{
    protected $chatbotService;

    public function __construct()
    {
        $this->chatbotService = new SIPSChatbotService();
    }

    /**
     * Get landing page data for chatbot context
     */
    private function getLandingPageData()
    {
        $today = date('Y-m-d');
        $weekAgo = date('Y-m-d', strtotime('-7 days'));

        // Get category IDs
        $kategoriRinganIds = DB::table('kategori_pelanggarans')
            ->where('nama', 'Ringan')
            ->pluck('id')
            ->toArray();

        $kategoriSedangIds = DB::table('kategori_pelanggarans')
            ->where('nama', 'Sedang')
            ->pluck('id')
            ->toArray();

        $kategoriBeratIds = DB::table('kategori_pelanggarans')
            ->where('nama', 'Berat')
            ->pluck('id')
            ->toArray();

        // Get violation type IDs
        $jenisRinganIds = DB::table('jenis_pelanggarans')
            ->whereIn('id_kategori_pelanggaran', $kategoriRinganIds)
            ->pluck('id')
            ->toArray();

        $jenisSedangIds = DB::table('jenis_pelanggarans')
            ->whereIn('id_kategori_pelanggaran', $kategoriSedangIds)
            ->pluck('id')
            ->toArray();

        $jenisBeratIds = DB::table('jenis_pelanggarans')
            ->whereIn('id_kategori_pelanggaran', $kategoriBeratIds)
            ->pluck('id')
            ->toArray();

        // Chart data for 7 days
        $chartData = [
            'ringan' => [],
            'sedang' => [],
            'berat' => []
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));

            $chartData['ringan'][] = !empty($jenisRinganIds)
                ? DB::table('pelanggarans')
                    ->whereDate('created_at', $date)
                    ->whereIn('id_jenis_pelanggaran', $jenisRinganIds)
                    ->count()
                : 0;

            $chartData['sedang'][] = !empty($jenisSedangIds)
                ? DB::table('pelanggarans')
                    ->whereDate('created_at', $date)
                    ->whereIn('id_jenis_pelanggaran', $jenisSedangIds)
                    ->count()
                : 0;

            $chartData['berat'][] = !empty($jenisBeratIds)
                ? DB::table('pelanggarans')
                    ->whereDate('created_at', $date)
                    ->whereIn('id_jenis_pelanggaran', $jenisBeratIds)
                    ->count()
                : 0;
        }

        // Class statistics
        $kelasData = DB::table('pelanggarans')
            ->select('siswas.kelas', DB::raw('COUNT(DISTINCT pelanggarans.id_siswa) as jumlah_siswa_pelaku'))
            ->join('siswas', 'pelanggarans.id_siswa', '=', 'siswas.id')
            ->where('pelanggarans.created_at', '>=', $weekAgo)
            ->groupBy('siswas.kelas')
            ->orderBy('siswas.kelas')
            ->get();

        $kelasPelanggaran = DB::table('pelanggarans')
            ->select('siswas.kelas', DB::raw('COUNT(pelanggarans.id) as jumlah_pelanggaran'))
            ->join('siswas', 'pelanggarans.id_siswa', '=', 'siswas.id')
            ->where('pelanggarans.created_at', '>=', $weekAgo)
            ->groupBy('siswas.kelas')
            ->orderBy('siswas.kelas')
            ->get()
            ->keyBy('kelas');

        $kelasStats = $kelasData->map(function ($item) use ($kelasPelanggaran) {
            $item->jumlah_pelanggaran = $kelasPelanggaran->get($item->kelas)->jumlah_pelanggaran ?? 0;
            return $item;
        });

        // Get school name
        $namaSekolah = Setting::get('nama_sekolah', 'SMA Negeri');

        return [
            'chartData' => $chartData,
            'kelasStats' => $kelasStats,
            'nama_sekolah' => $namaSekolah
        ];
    }

    /**
     * Handle chat message from user
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|min:1|max:500'
        ], [
            'message.required' => 'Pesan tidak boleh kosong.',
            'message.max' => 'Pesan terlalu panjang. Maksimal 500 karakter.'
        ]);

        try {
            $message = $request->input('message');
            $landingData = $this->getLandingPageData();

            $response = $this->chatbotService->getResponse($message, $landingData);

            return response()->json([
                'success' => true,
                'data' => $response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan. Silakan coba lagi.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get initial greeting message
     */
    public function greet()
    {
        $landingData = $this->getLandingPageData();
        $response = $this->chatbotService->getResponse('halo', $landingData);

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    /**
     * Get quick reply response
     */
    public function quickReply(Request $request)
    {
        $request->validate([
            'message' => 'required|string|min:1|max:500'
        ]);

        try {
            $message = $request->input('message');
            $landingData = $this->getLandingPageData();

            $response = $this->chatbotService->getResponse($message, $landingData);

            return response()->json([
                'success' => true,
                'data' => $response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan. Silakan coba lagi.'
            ], 500);
        }
    }
}

