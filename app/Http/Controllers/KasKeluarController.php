<?php

namespace App\Http\Controllers;

use App\Models\KasKeluar;
use App\Models\Jurnal;
use App\Models\Projek;
use App\Models\COA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class KasKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    
        $kaskeluar = KasKeluar::with('projek', 'coa')->get();    
        return view('KasKeluar.view', compact('kaskeluar'));    
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
        $noKasKeluar = KasKeluar::generateNoKasKeluar();
        return view('KasKeluar.create', compact('projek', 'noKasKeluar', 'coa'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    
        Log::info('Store KasKeluar Request Data:', $request->all());

        // Validasi input
        $validated = $request->validate([
            'no_kaskeluar' => 'required|unique:kaskeluar,no_kaskeluar',
            'tanggal' => 'required|date',
            'keterangan' => 'required|max:50',
            'tipe' => 'required',
            'jumlah' => 'required|integer|min:0',
            'projek_id' => 'required|exists:projek,id',
            'coa_id' => 'required|exists:coa,id',
            'evidence' => 'nullable|file|mimes:jpg,jpeg,pdf',
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
            $kaskeluar = KasKeluar::create([
                'no_kaskeluar' => $validated['no_kaskeluar'],
                'tanggal' => $validated['tanggal'],
                'keterangan' => $validated['keterangan'],
                'tipe' => $validated['tipe'],
                'jumlah' => $validated['jumlah'],
                'projek_id' => $validated['projek_id'],
                'coa_id' => $validated['coa_id'],
                'evidence' => $filePath,
                'original_filename' => $originalFilename,
            ]);

            Log::info('KasKeluar created:', $kaskeluar->toArray());

            // Buat jurnal debit beban
            Jurnal::create([
                'id_transaksi' => $kaskeluar->id,
                'kode_akun' => '511', // Akun Beban
                'tgl_jurnal' => $validated['tanggal'],
                'posisi_d_c' => 'd',
                'nominal' => $validated['jumlah'],
                'kelompok' => '5',
                'transaksi' => 'kas_keluar'
            ]);

            // Buat jurnal kredit kas
            Jurnal::create([
                'id_transaksi' => $kaskeluar->id,
                'kode_akun' => '111', // Akun Kas
                'tgl_jurnal' => $validated['tanggal'],
                'posisi_d_c' => 'c',
                'nominal' => $validated['jumlah'],
                'kelompok' => '1',
                'transaksi' => 'kas_keluar'
            ]);

            // Redirect dengan pesan sukses
            return redirect()->route('KasKeluar.index')->with('success', 'Data kas keluar berhasil ditambahkan.');
        
        } catch (\Exception $e) {
            // Hapus file yang sudah diupload jika terjadi kesalahan
            if ($filePath) {
                Storage::delete($filePath);
            }

            Log::error('Error creating KasKeluar: ' . $e->getMessage());

            // Redirect dengan pesan error
            return redirect()->back()
                ->with('error', 'Gagal menyimpan data kas keluar: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KasKeluar  $kaskeluar
     * @return \Illuminate\Http\Response
     */
    public function show(KasKeluar $kaskeluar)
    {
        return view('KasKeluar.show', compact('kaskeluar'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\KasKeluar  $kaskeluar
     * @return \Illuminate\Http\Response
     */
    // public function edit(KasKeluar $kaskeluar)
    // {
    //     $projek = Projek::all();
    //     $coa = COA::all();
    //     return view('KasKeluar.edit', compact('kaskeluar', 'projek', 'coa'));
    // }

    public function edit($id)
    {
        $kaskeluar = KasKeluar::findOrFail($id);
        $projek = Projek::all();
        $coa = COA::all();
        return view('KasKeluar.edit', compact('kaskeluar', 'projek', 'coa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KasKeluar  $kaskeluar
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, KasKeluar $kaskeluar)
    public function update(Request $request, $id)
    {
    $kaskeluar = KasKeluar::findOrFail($id);
    
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
            // Perbarui data kas keluar
            $kaskeluar->update([
                'tanggal' => $validated['tanggal'],
                'keterangan' => $validated['keterangan'],
                'tipe' => $validated['tipe'],
                'jumlah' => $validated['jumlah'],
                'projek_id' => $validated['projek_id'],
                'coa_id' => $validated['coa_id'],
            ]);

            // Perbarui nominal di jurnal
            Jurnal::where('id_transaksi', $kaskeluar->id)
                ->where('transaksi', 'kas_keluar')
                ->update([
                    'nominal' => $validated['jumlah'],
                    'tgl_jurnal' => $validated['tanggal']
                ]);

            // Jika ada file evidence baru yang diunggah
            if ($request->hasFile('evidence')) {
                // Hapus file evidence lama jika ada
                if ($kaskeluar->evidence) {
                    Storage::delete('public/' . $kaskeluar->evidence);
                }

                // Simpan file evidence baru
                $filePath = $request->file('evidence')->store('evidence', 'public');
                $originalFilename = $request->file('evidence')->getClientOriginalName();

                // Perbarui path file dan nama file asli
                $kaskeluar->update([
                    'evidence' => $filePath,
                    'original_filename' => $originalFilename,
                ]);
            }

            // Redirect atau return response
            return redirect()->route('KasKeluar.index')->with('success', 'Data kas keluar berhasil diperbarui.');
        
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data kas keluar: ' . $e->getMessage())
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
            $kasKeluar = KasKeluar::findOrFail($id);

            // Hapus file evidence jika ada
            if ($kasKeluar->evidence) {
                Storage::delete('public/' . $kasKeluar->evidence);
            }

            // Hapus jurnal terkait
            Jurnal::where('id_transaksi', $kasKeluar->id)
                ->where('transaksi', 'kas_keluar')
                ->delete();

            // Hapus kas keluar
            $kasKeluar->delete();

            return redirect()->route('KasKeluar.index')->with('success', 'Data kas keluar berhasil dihapus.');
        
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data kas keluar: ' . $e->getMessage());
        }
    }
}