<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KasMasuk;
use App\Models\KasKeluar;
use App\Models\Projek;
use App\Models\JurnalUmum;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class JurnalUmumController extends Controller
{
    public function index()
    {
        // Ambil semua proyek untuk dropdown
        $projekList = Projek::all();
        return view('laporan.jurnalumum', compact('projekList'));
    }
    
    public function getData(Request $request)
    {
        try {
            // Validasi request dengan pesan error yang lebih spesifik
            $validated = $request->validate([
                'periode' => 'required|string|regex:/^\d{4}-\d{2}$/',
                'projek_id' => 'nullable|exists:projek,id'
            ], [
                'periode.required' => 'Periode harus diisi',
                'periode.regex' => 'Format periode harus YYYY-MM (contoh: 2025-03)'
            ]);
            
            // Parse periode
            $periode = explode('-', $request->periode);
            $tahun = $periode[0];
            $bulan = $periode[1];
            
            // Ambil data kas keluar untuk periode yang dipilih
            $kasKeluar = KasKeluar::with('coa')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->when($request->projek_id, function ($query) use ($request) {
                    return $query->where('projek_id', $request->projek_id);
                })
                ->get();
                
            // Ambil data kas masuk untuk periode yang dipilih
            $kasMasuk = KasMasuk::with('coa')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->when($request->projek_id, function ($query) use ($request) {
                    return $query->where('projek_id', $request->projek_id);
                })
                ->get();
            
            // Format data untuk jurnal umum
            $jurnalData = [];
            
            // Hapus data jurnal umum yang sudah ada untuk periode ini
            DB::beginTransaction();
            JurnalUmum::whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->when($request->projek_id, function ($query) use ($request) {
                    return $query->where('projek_id', $request->projek_id);
                })
                ->delete();
            
            // Format data kas masuk
            foreach ($kasMasuk as $km) {
                try {
                    // Ambil nama akun dari relasi coa
                    $namaAkun = $km->coa->nama_akun;
                    $noRef = $km->coa->kode_akun;
                    
                    // Entry untuk debit (Kas)
                    $entryDebit = [
                        'id_jurnal' => $km->no_kasmasuk,
                        'tanggal' => $km->tanggal,
                        'akun_debit' => 'Kas',
                        'akun_kredit' => '',
                        'ref' => '1110',
                        'debet' => $km->jumlah,
                        'kredit' => 0,
                        'keterangan' => $km->keterangan,
                        'projek_id' => $km->projek_id
                    ];
                    
                    // Entry untuk kredit (Akun yang dipilih user)
                    $entryKredit = [
                        'id_jurnal' => $km->no_kasmasuk,
                        'tanggal' => $km->tanggal,
                        'akun_debit' => '',
                        'akun_kredit' => $namaAkun,
                        'ref' => $noRef,
                        'debet' => 0,
                        'kredit' => $km->jumlah,
                        'keterangan' => $km->keterangan,
                        'projek_id' => $km->projek_id
                    ];
                    
                    // Simpan ke database
                    $this->saveJurnal($entryDebit);
                    $this->saveJurnal($entryKredit);
                    
                    // Tambahkan ke data jurnal untuk response
                    $jurnalData[] = $entryDebit;
                    $jurnalData[] = $entryKredit;
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Error saat memproses kas masuk', [
                        'kas_masuk_id' => $km->id,
                        'error' => $e->getMessage()
                    ]);
                    throw $e;
                }
            }
            
            // Format data kas keluar
            foreach ($kasKeluar as $kk) {
                try {
                    // Ambil nama akun dari relasi coa
                    $namaAkun = $kk->coa->nama_akun;
                    $noRef = $kk->coa->kode_akun;
                    
                    // Entry untuk debit (Akun yang dipilih user)
                    $entryDebit = [
                        'id_jurnal' => $kk->no_kaskeluar,
                        'tanggal' => $kk->tanggal,
                        'akun_debit' => $namaAkun,
                        'akun_kredit' => '',
                        'ref' => $noRef,
                        'debet' => $kk->jumlah,
                        'kredit' => 0,
                        'keterangan' => $kk->keterangan,
                        'projek_id' => $kk->projek_id
                    ];
                    
                    // Entry untuk kredit (Kas)
                    $entryKredit = [
                        'id_jurnal' => $kk->no_kaskeluar,
                        'tanggal' => $kk->tanggal,
                        'akun_debit' => '',
                        'akun_kredit' => 'Kas',
                        'ref' => '1110',
                        'debet' => 0,
                        'kredit' => $kk->jumlah,
                        'keterangan' => $kk->keterangan,
                        'projek_id' => $kk->projek_id
                    ];
                    
                    // Simpan ke database
                    $this->saveJurnal($entryDebit);
                    $this->saveJurnal($entryKredit);
                    
                    // Tambahkan ke data jurnal untuk response
                    $jurnalData[] = $entryDebit;
                    $jurnalData[] = $entryKredit;
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Error saat memproses kas keluar', [
                        'kas_keluar_id' => $kk->id,
                        'error' => $e->getMessage()
                    ]);
                    throw $e;
                }
            }
            
            // Commit transaction jika semua berhasil
            DB::commit();
            
            // Urutkan data berdasarkan tanggal
            usort($jurnalData, function($a, $b) {
                return strtotime($a['tanggal']) - strtotime($b['tanggal']);
            });
            
            return response()->json([
                'success' => true,
                'data' => $jurnalData,
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data yang diberikan tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Rollback if transaction was started
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            
            Log::error('Error pada getData', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Simpan data jurnal ke database
     */
    private function saveJurnal($data)
    {
        try {
            // Buat array untuk create model
            $jurnalData = [
                'id_jurnal' => $data['id_jurnal'],
                'tanggal' => $data['tanggal'],
                'akun_debit' => $data['akun_debit'],
                'akun_kredit' => $data['akun_kredit'],
                'ref' => $data['ref'],
                'debet' => $data['debet'],
                'kredit' => $data['kredit'],
                'keterangan' => $data['keterangan'],
                'projek_id' => $data['projek_id'],
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            // Simpan ke database
            JurnalUmum::create($jurnalData);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan data jurnal', [
                'data' => $data,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }
}