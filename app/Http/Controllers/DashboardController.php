<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung Total Kas Masuk
        $totalKasMasuk = DB::table('kas_masuk')
            ->sum('jumlah');

        // Hitung Total Kas Keluar
        $totalKasKeluar = DB::table('kaskeluar')
            ->sum('jumlah');

        // Hitung Persentase Perubahan Kas Masuk
        $bulanSebelumnya = Carbon::now()->subMonth();
        $totalKasMasukBulanSebelumnya = DB::table('kas_masuk')
            ->whereMonth('tanggal', $bulanSebelumnya->month)
            ->whereYear('tanggal', $bulanSebelumnya->year)
            ->sum('jumlah');
        
        $persenKasMasuk = $totalKasMasukBulanSebelumnya > 0 
            ? round(($totalKasMasuk - $totalKasMasukBulanSebelumnya) / $totalKasMasukBulanSebelumnya * 100, 2)
            : 0;

        // Perhitungan serupa untuk Kas Keluar
        $totalKasKeluarBulanSebelumnya = DB::table('kaskeluar')
            ->whereMonth('tanggal', $bulanSebelumnya->month)
            ->whereYear('tanggal', $bulanSebelumnya->year)
            ->sum('jumlah');
        
        $persenKasKeluar = $totalKasKeluarBulanSebelumnya > 0 
            ? round(($totalKasKeluar - $totalKasKeluarBulanSebelumnya) / $totalKasKeluarBulanSebelumnya * 100, 2)
            : 0;

        // Saldo Kas
        $saldoKas = $totalKasMasuk - $totalKasKeluar;

        // Jumlah Proyek
        $jumlahProyek = DB::table('projek')->count();

        // Proyek Terbaru
        $proyekTerbaru = DB::table('projek')
    ->select('projek.nama_projek', 
        DB::raw('(
            SELECT COALESCE(
                SUM(CASE 
                    WHEN LOWER(akun_debit) LIKE "%kas%" THEN debet 
                    WHEN LOWER(akun_kredit) LIKE "%kas%" THEN -kredit 
                    ELSE 0 
                END), 0) 
            FROM jurnal_umum 
            WHERE projek_id = projek.id 
            AND (LOWER(akun_debit) LIKE "%kas%" OR LOWER(akun_kredit) LIKE "%kas%")
            AND deleted_at IS NULL
        ) as total_kas'))
    ->orderBy('total_kas', 'desc')
    ->limit(3)
    ->get();

        // Transaksi Terakhir (Kas Masuk)
        $transaksiMasukTerakhir = DB::table('kas_masuk')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        // Transaksi Terakhir (Kas Keluar)
        $transaksiKeluarTerakhir = DB::table('kaskeluar')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        // Grafik Aliran Kas untuk 6 bulan terakhir
        $grafikKasMasuk = [];
        $grafikKasKeluar = [];
        $labelBulan = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $labelBulan[] = $bulan->format('M');

            $kasMasuk = DB::table('kas_masuk')
                ->whereMonth('tanggal', $bulan->month)
                ->whereYear('tanggal', $bulan->year)
                ->sum('jumlah');
            
            $kasKeluar = DB::table('kaskeluar')
                ->whereMonth('tanggal', $bulan->month)
                ->whereYear('tanggal', $bulan->year)
                ->sum('jumlah');

            $grafikKasMasuk[] = $kasMasuk;
            $grafikKasKeluar[] = $kasKeluar;
        }

        // Kirim data ke view
        return view('dashboardbootstrap', compact(
            'totalKasMasuk', 
            'totalKasKeluar', 
            'persenKasMasuk', 
            'persenKasKeluar',
            'saldoKas', 
            'jumlahProyek', 
            'proyekTerbaru', 
            'transaksiMasukTerakhir',
            'transaksiKeluarTerakhir',
            'grafikKasMasuk',
            'grafikKasKeluar',
            'labelBulan'
        ));
    }
}