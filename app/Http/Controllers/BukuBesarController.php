<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JurnalUmum;
use App\Models\COA;
use App\Models\Projek;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BukuBesarController extends Controller
{
    public function index(Request $request)
    {
        return $this->getData($request);
    }

    private function getPosisiSaldoNormal($kodeAkun)
    {
        $headerAkun = substr($kodeAkun, 0, 1);
        switch ($headerAkun) {
            case '1':
            case '5':
                return 'debet';
            case '2':
            case '3':
            case '4':
                return 'kredit';
            default:
                return 'debet';
        }
    }

    public function getData(Request $request, $defaultPeriode = null, $defaultAkun = null)
    {
        $periode = $request->periode ?? $defaultPeriode;
        $akun = $request->akun ?? $defaultAkun;
        $projek = $request->projek ?? null;
        
        if (!$periode || !$akun) {
            $akun = COA::orderBy('kode_akun')->get();
            $projeks = Projek::all();
            return view('laporan.bukubesar', [
                'akun' => $akun,
                'projeks' => $projeks,
                'showEmptyTable' => true,
                'periode' => date('Y-m'),
            ]);
        }

        $tanggalAwal = Carbon::createFromFormat('Y-m', $periode)->startOfMonth();
        $tanggalAkhir = Carbon::createFromFormat('Y-m', $periode)->endOfMonth();
        $periodeBulan = Carbon::createFromFormat('Y-m', $periode)->format('F Y');

        $dataAkun = COA::where('id', $akun)->first();
        
        if ($dataAkun) {
            // Hitung saldo awal sebelum periode - tambahkan whereNull('deleted_at')
            $saldoAwalQuery = JurnalUmum::where(function($query) use ($dataAkun) {
                $query->whereRaw('LOWER(akun_debit) = ?', [strtolower($dataAkun->nama_akun)])
                      ->orWhereRaw('LOWER(akun_kredit) = ?', [strtolower($dataAkun->nama_akun)]);
            })
            ->whereNull('deleted_at')  // Add soft delete check
            ->where('tanggal', '<', $tanggalAwal);

            // Tambahkan filter projek untuk saldo awal jika dipilih
            if ($projek) {
                $saldoAwalQuery->where('projek_id', $projek);
            }

            $saldoAwalDebet = $saldoAwalQuery->sum('debet');
            $saldoAwalKredit = $saldoAwalQuery->sum('kredit');

            // Tentukan posisi saldo normal
            $posisiSaldoNormal = $this->getPosisiSaldoNormal($dataAkun->kode_akun);

            // Hitung saldo awal
            if ($posisiSaldoNormal == 'debet') {
                $saldoAwal = $saldoAwalDebet - $saldoAwalKredit;
            } else {
                $saldoAwal = $saldoAwalKredit - $saldoAwalDebet;
            }

            // Ambil transaksi pada periode yang dipilih - tambahkan whereNull('deleted_at')
            $transaksiQuery = JurnalUmum::where(function($query) use ($dataAkun) {
                $query->whereRaw('LOWER(akun_debit) = ?', [strtolower($dataAkun->nama_akun)])
                      ->orWhereRaw('LOWER(akun_kredit) = ?', [strtolower($dataAkun->nama_akun)]);
            })
            ->whereNull('deleted_at')  // Add soft delete check
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);

            // Tambahkan filter projek jika dipilih
            if ($projek) {
                $transaksiQuery->where('projek_id', $projek);
            }

            $transaksi = $transaksiQuery->orderBy('tanggal')->get();

            // Siapkan variabel untuk tracking saldo
            $saldoAkhir = $saldoAwal;

            // Tambahkan informasi saldo untuk setiap transaksi
            foreach ($transaksi as $t) {
                // Untuk akun dengan posisi debet (1 dan 5)
                if ($posisiSaldoNormal == 'debet') {
                    if (strtolower($t->akun_debit) === strtolower($dataAkun->nama_akun)) {
                        $saldoAkhir += $t->debet;
                        $t->saldo_debet = $saldoAkhir;
                        $t->saldo_kredit = 0;
                    } 
                    
                    if (strtolower($t->akun_kredit) === strtolower($dataAkun->nama_akun)) {
                        $saldoAkhir -= $t->kredit;
                        $t->saldo_debet = $saldoAkhir;
                        $t->saldo_kredit = 0;
                    }
                } 
                // Untuk akun dengan posisi kredit (2, 3, 4)
                else {
                    if (strtolower($t->akun_kredit) === strtolower($dataAkun->nama_akun)) {
                        $saldoAkhir += $t->kredit;
                        $t->saldo_kredit = $saldoAkhir;
                        $t->saldo_debet = 0;
                    } 
                    
                    if (strtolower($t->akun_debit) === strtolower($dataAkun->nama_akun)) {
                        $saldoAkhir -= $t->debet;
                        $t->saldo_kredit = $saldoAkhir;
                        $t->saldo_debet = 0;
                    }
                }
            }

        } else {
            $saldoAwal = 0;
            $saldoAkhir = 0;
            $transaksi = collect();
            
            Log::warning('BukuBesar: Akun tidak ditemukan', ['akun_id' => $akun]);
        }

        // Tambahkan query untuk mendapatkan proyek
        $projeks = Projek::all();

        return view('laporan.bukubesar', [
            'akun' => COA::orderBy('kode_akun')->get(),
            'projeks' => $projeks,
            'dataAkun' => $dataAkun ?? null,
            'tanggalAwal' => $tanggalAwal ?? null,
            'tanggalAkhir' => $tanggalAkhir ?? null,
            'periodeBulan' => $periodeBulan,
            'transaksi' => $transaksi ?? collect(),
            'saldoAwal' => $saldoAwal ?? 0,
            'saldoAkhir' => $saldoAkhir ?? 0,
            'periode' => $periode,
            'selectedAkun' => $akun,
            'selectedProjek' => $projek,
            'showEmptyTable' => true,
        ]);
    }
}