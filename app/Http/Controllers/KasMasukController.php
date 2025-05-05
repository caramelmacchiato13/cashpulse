<?php

namespace App\Http\Controllers;

use App\Models\KasMasuk;
use App\Models\Jurnal;
use App\Models\Projek;
use App\Models\COA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class KasMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    
        $kasmasuk = KasMasuk::with('projek', 'coa')->get();    
        return view('kasmasuk.view', compact('kasmasuk'));    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projek = Projek::all();
        $coa = COA::all();
        $noKasMasuk = KasMasuk::generateNoKasMasuk();
        return view('kasmasuk.create', compact('projek', 'noKasMasuk', 'coa'));
    }

    public function getAccountsByType(Request $request)
    {
        $selectedType = $request->input('tipe');
        $accounts = COA::where('tipe', $selectedType)
                        ->select('kode_akun', 'nama_akun')
                        ->get();
        return response()->json($accounts);
    }

    /**
     * Generate unique KasMasuk number
     *
     * @return string
     */
    // private function generateNoKasMasuk()
    // {
    //     $prefix = 'KM-' . date('Ymd') . '-';
    //     $lastRecord = KasMasuk::where('no_kasmasuk', 'like', $prefix . '%')
    //         ->orderBy('no_kasmasuk', 'desc')
    //         ->first();

    //     $nextNumber = $lastRecord 
    //         ? intval(substr($lastRecord->no_kasmasuk, -4)) + 1 
    //         : 1;

    //     return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    
        Log::info('Store KasMasuk Request Data:', $request->all());

        // Validasi input
        $validated = $request->validate([
            'no_kasmasuk' => 'required|unique:kas_masuk,no_kasmasuk',
            'tanggal' => 'required|date',
            'keterangan' => 'required|max:50',
            'tipe' => 'required',
            'jumlah' => 'required|integer|min:0',
            'projek_id' => 'required|exists:projek,id',
            'coa_id' => 'required|exists:coa,id',
            'evidence' => 'nullable|file|mimes:jpg,jpeg,pdf|max:2048',
        ]);

        Log::info('Validated Data:', $validated);

        try {
            // Menyimpan file evidence jika ada
            $filePath = null;
            $originalFilename = null;
            if ($request->hasFile('evidence')) {
                $filePath = $request->file('evidence')->store('evidence', 'public');
                $originalFilename = $request->file('evidence')->getClientOriginalName();
            }

            // Membuat entri baru
            $kasmasuk = KasMasuk::create([
                'no_kasmasuk' => $validated['no_kasmasuk'],
                'tanggal' => $validated['tanggal'],
                'keterangan' => $validated['keterangan'],
                'tipe' => $validated['tipe'],
                'jumlah' => $validated['jumlah'],
                'projek_id' => $validated['projek_id'],
                'coa_id' => $validated['coa_id'],
                'evidence' => $filePath,
                'original_filename' => $originalFilename,
            ]);

            Log::info('KasMasuk created:', $kasmasuk->toArray());

            // Buat jurnal debit kas
            Jurnal::create([
                'id_transaksi' => $kasmasuk->id,
                'kode_akun' => '111', // Akun Kas
                'tgl_jurnal' => $validated['tanggal'],
                'posisi_d_c' => 'd',
                'nominal' => $validated['jumlah'],
                'kelompok' => '1',
                'transaksi' => 'kas_masuk'
            ]);

            // Buat jurnal kredit pendapatan
            Jurnal::create([
                'id_transaksi' => $kasmasuk->id,
                'kode_akun' => '411', // Akun Pendapatan
                'tgl_jurnal' => $validated['tanggal'],
                'posisi_d_c' => 'c',
                'nominal' => $validated['jumlah'],
                'kelompok' => '4',
                'transaksi' => 'kas_masuk'
            ]);

            // Redirect dengan pesan sukses
            return redirect()->route('kasmasuk.index')->with('success', 'Data kas masuk berhasil ditambahkan.');
        
        } catch (\Exception $e) {
            // Hapus file yang sudah diupload jika terjadi kesalahan
            if ($filePath) {
                Storage::delete($filePath);
            }

            Log::error('Error creating KasMasuk: ' . $e->getMessage());

            // Redirect dengan pesan error
            return redirect()->back()
                ->with('error', 'Gagal menyimpan data kas masuk: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KasMasuk  $kasmasuk
     * @return \Illuminate\Http\Response
     */
    public function show(KasMasuk $kasmasuk)
    {
        return view('kasmasuk.show', compact('kasmasuk'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\KasMasuk  $kasmasuk
     * @return \Illuminate\Http\Response
     */
    public function edit(KasMasuk $kasmasuk)
    {
        $projek = Projek::all();
        $coa = COA::all();
        return view('kasmasuk.edit', compact('kasmasuk', 'projek', 'coa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KasMasuk  $kasmasuk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KasMasuk $kasmasuk)
    {
        // Validasi input
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|max:50',
            'tipe' => 'required',
            'jumlah' => 'required|integer|min:0',
            'projek_id' => 'required|exists:projek,id',
            'coa_id' => 'required|exists:coa,id',
            'evidence' => 'nullable|file|mimes:jpg,jpeg,pdf|max:2048',
        ]);

        try {
            // Perbarui data kas masuk
            $kasmasuk->update([
                'tanggal' => $validated['tanggal'],
                'keterangan' => $validated['keterangan'],
                'tipe' => $validated['tipe'],
                'jumlah' => $validated['jumlah'],
                'projek_id' => $validated['projek_id'],
                'coa_id' => $validated['coa_id'],
            ]);

            // Perbarui nominal di jurnal
            Jurnal::where('id_transaksi', $kasmasuk->id)
                ->where('transaksi', 'kas_masuk')
                ->update([
                    'nominal' => $validated['jumlah'],
                    'tgl_jurnal' => $validated['tanggal']
                ]);

            // Jika ada file evidence baru yang diunggah
            if ($request->hasFile('evidence')) {
                // Hapus file evidence lama jika ada
                if ($kasmasuk->evidence) {
                    Storage::delete('public/' . $kasmasuk->evidence);
                }

                // Simpan file evidence baru
                $filePath = $request->file('evidence')->store('evidence', 'public');
                $originalFilename = $request->file('evidence')->getClientOriginalName();

                // Perbarui path file dan nama file asli
                $kasmasuk->update([
                    'evidence' => $filePath,
                    'original_filename' => $originalFilename,
                ]);
            }

            // Redirect atau return response
            return redirect()->route('kasmasuk.index')->with('success', 'Data kas masuk berhasil diperbarui.');
        
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data kas masuk: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $kasMasuk = KasMasuk::findOrFail($id);

            // Hapus file evidence jika ada
            if ($kasMasuk->evidence) {
                Storage::delete('public/' . $kasMasuk->evidence);
            }

            // Hapus jurnal terkait
            Jurnal::where('id_transaksi', $kasMasuk->id)
                ->where('transaksi', 'kas_masuk')
                ->delete();

            // Hapus kas masuk
            $kasMasuk->delete();

            return redirect()->route('kasmasuk.index')->with('success', 'Data kas masuk berhasil dihapus.');
        
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data kas masuk: ' . $e->getMessage());
        }
    }
}