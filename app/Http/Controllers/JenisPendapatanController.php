<?php

namespace App\Http\Controllers;

use App\Models\jenis_pendapatan;
use App\Http\Requests\Storejenis_pendapatanRequest;
use App\Http\Requests\Updatejenis_pendapatanRequest;

class JenisPendapatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $jenis_pendapatan = jenis_pendapatan::all();
        return view('jenis_pendapatan.view',
                     [
                        'jenispendapatan'=>$jenis_pendapatan,
                     ]   
                    );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jenis_pendapatan/create',
        [
            'kode_jenis_pendapatan' => jenis_pendapatan::getKodeJenisPendapatan()
        ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Storejenis_pendapatanRequest $request)
    {
        {
            //digunakan untuk validasi kemudian kalau ok tidak ada masalah baru disimpan ke db
            $validated = $request->validate([
                'kode_jenis_pendapatan' => 'required',
                'sumber_pendapatan' => 'required|unique:jenis_pendapatan|min:5|max:255',
                'tanggal_pendapatan' => 'required',
            ]);
    
            // masukkan ke db
            jenis_pendapatan::create($request->all());
            
            return redirect()->route('jenispendapatan.index')->with('success','Data Berhasil di Input');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(jenis_pendapatan $jenis_pendapatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(jenis_pendapatan $jenispendapatan)
    {
        return view('jenis_pendapatan.edit', compact('jenispendapatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updatejenis_pendapatanRequest $request, jenis_pendapatan $jenis_pendapatan)
    {
        $validated = $request->validate([
            'kode_jenis_pendapatan' => 'required',
            'sumber_pendapatan' => 'required|max:255',
            'tanggal_pendapatan' => 'required',
        ]);
    
        $jenis_pendapatan->update($validated);
    
        return redirect()->route('jenispendapatan.index')->with('success','Data Berhasil di Ubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jenis_pendapatan = Jenis_Pendapatan::findOrFail($id);
        $jenis_pendapatan->delete();

        return redirect()->route('jenispendapatan.index')->with('success','Data Berhasil di Hapus');
    }
}
