<?php

namespace App\Http\Controllers;

use App\Models\Aruskas;
use App\Models\KasMasuk;
use App\Models\KasKeluar;
use App\Models\Projek;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use PDF;

class AruskasController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $projek_id = $request->input('projek_id');
        
        // Create period object for the view
        $periode = Carbon::createFromDate($tahun, $bulan, 1);
        $projeks = Projek::all();
        
        // Initialize variables with empty/zero values
        $kasmasuk_operasional_rinci = collect();
        $kasmasuk_investasi_rinci = collect();
        $kasmasuk_pendanaan_rinci = collect();
        $kaskeluar_operasional_rinci = collect();
        $kaskeluar_investasi_rinci = collect();
        $kaskeluar_pendanaan_rinci = collect();
        $kas_awal = 0;
        $kas_akhir = 0;
        $total_perubahan = 0;
        $kasmasuk_operasional = 0;
        $kasmasuk_investasi = 0;
        $kasmasuk_pendanaan = 0;
        $kaskeluar_operasional = 0;
        $kaskeluar_investasi = 0;
        $kaskeluar_pendanaan = 0;
        $selectedProjek = null;
        
        // Only process data if a project is explicitly selected
        if ($request->filled('projek_id')) {
            $selectedProjek = Projek::find($projek_id);
            
            // STEP 1: Calculate the opening balance for the selected period
            // First, find the start date of the period
            $start_of_period = Carbon::createFromDate($tahun, $bulan, 1)->startOfDay();
            
            // Calculate all income and expenses BEFORE this period for this project
            $all_previous_income = KasMasuk::where('projek_id', $projek_id)
                ->where('tanggal', '<', $start_of_period)
                ->sum('jumlah');
                
            $all_previous_expenses = KasKeluar::where('projek_id', $projek_id)
                ->where('tanggal', '<', $start_of_period)
                ->sum('jumlah');
                
            // This is the true opening balance based on all previous transactions
            $kas_awal = $all_previous_income - $all_previous_expenses;
            
            // STEP 2: Calculate the current period's transactions
            // Query for kas masuk with data existence check
            $kasmasukQuery = KasMasuk::whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->where('projek_id', $projek_id);

            // Query for kas keluar with data existence check
            $kaskeluarQuery = KasKeluar::whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->where('projek_id', $projek_id);

            // Detailed kas masuk using cloned query
            $kasmasuk_operasional_rinci = (clone $kasmasukQuery)
                ->where('tipe', 'Arus kas dari aktivitas operasional')
                ->select('keterangan', DB::raw('SUM(jumlah) as total'))
                ->groupBy('keterangan')
                ->get();
                
            $kasmasuk_investasi_rinci = (clone $kasmasukQuery)
                ->where('tipe', 'Arus kas dari aktivitas investasi')
                ->select('keterangan', DB::raw('SUM(jumlah) as total'))
                ->groupBy('keterangan')
                ->get();
                
            $kasmasuk_pendanaan_rinci = (clone $kasmasukQuery)
                ->where('tipe', 'Arus kas dari aktivitas pendanaan')
                ->select('keterangan', DB::raw('SUM(jumlah) as total'))
                ->groupBy('keterangan')
                ->get();

            // Detailed kas keluar using cloned query
            $kaskeluar_operasional_rinci = (clone $kaskeluarQuery)
                ->where('tipe', 'Arus kas dari aktivitas operasional')
                ->select('keterangan', DB::raw('SUM(jumlah) as total'))
                ->groupBy('keterangan')
                ->get();
                
            $kaskeluar_investasi_rinci = (clone $kaskeluarQuery)
                ->where('tipe', 'Arus kas dari aktivitas investasi')
                ->select('keterangan', DB::raw('SUM(jumlah) as total'))
                ->groupBy('keterangan')
                ->get();
                
            $kaskeluar_pendanaan_rinci = (clone $kaskeluarQuery)
                ->where('tipe', 'Arus kas dari aktivitas pendanaan')
                ->select('keterangan', DB::raw('SUM(jumlah) as total'))
                ->groupBy('keterangan')
                ->get();

            // Calculate totals
            $kasmasuk_operasional = $kasmasuk_operasional_rinci->sum('total');
            $kasmasuk_investasi = $kasmasuk_investasi_rinci->sum('total');
            $kasmasuk_pendanaan = $kasmasuk_pendanaan_rinci->sum('total');
            
            $kaskeluar_operasional = $kaskeluar_operasional_rinci->sum('total');
            $kaskeluar_investasi = $kaskeluar_investasi_rinci->sum('total');
            $kaskeluar_pendanaan = $kaskeluar_pendanaan_rinci->sum('total');

            // Calculate total cash change
            $total_perubahan = ($kasmasuk_operasional - $kaskeluar_operasional) +
                          ($kasmasuk_investasi - $kaskeluar_investasi) +
                          ($kasmasuk_pendanaan - $kaskeluar_pendanaan);
                            
            $kas_akhir = $kas_awal + $total_perubahan;

            // STEP 3: Save or update the cash flow record for this period
            Aruskas::updateOrCreate(
                [
                    'periode' => $periode->format('Y-m-d'),
                    'projek_id' => $projek_id
                ],
                [
                    'kas_awal' => $kas_awal,
                    'kas_akhir' => $kas_akhir
                ]
            );

            // Debug information
            \Log::info('Cash Flow Calculation:', [
                'Periode' => $periode->format('Y-m-d'),
                'Project ID' => $projek_id,
                'All Previous Income' => $all_previous_income,
                'All Previous Expenses' => $all_previous_expenses,
                'Opening Balance' => $kas_awal,
                'Current Period Change' => $total_perubahan,
                'Ending Balance' => $kas_akhir
            ]);
        }

        return view('laporan.arus-kas', compact(
            'periode',
            'projeks',
            'projek_id',
            'selectedProjek',
            'kasmasuk_operasional_rinci',
            'kasmasuk_investasi_rinci',
            'kasmasuk_pendanaan_rinci',
            'kaskeluar_operasional_rinci',
            'kaskeluar_investasi_rinci',
            'kaskeluar_pendanaan_rinci',
            'kasmasuk_operasional',
            'kasmasuk_investasi',
            'kasmasuk_pendanaan',
            'kaskeluar_operasional',
            'kaskeluar_investasi',
            'kaskeluar_pendanaan',
            'kas_awal',
            'kas_akhir',
            'total_perubahan'
        ));
    }
}