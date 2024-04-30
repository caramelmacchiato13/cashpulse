<?php

namespace App\Http\Controllers;

use App\Models\mitra;
use App\Http\Requests\StoremitraRequest;
use App\Http\Requests\UpdatemitraRequest;

class MitraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //query data
        $mitra = mitra::all();
        return view('mitra.view',
                    [
                        'mitra' => $mitra
                    ]
                  );
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        
        return view('mitra/create',
                    [
                        'kode_mitra' => mitra::getKodemitra()
                    ]
                  );
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoremitraRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoremitraRequest $request)
    {
        //digunakan untuk validasi kemudian kalau ok tidak ada masalah baru disimpan ke db
        $validated = $request->validate([
            'id_mitra' => 'required',
            'nama_mitra' => 'required|unique:mitra|min:5|max:255',
            'alamat_mitra' => 'required',
            'no_telp' => 'required',
            
        ]);

        // masukkan ke db
        mitra::create($request->all());
        
        return redirect()->route('mitra.index')->with('success','Data Berhasil di Input');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\mitra  $mitra
     * @return \Illuminate\Http\Response
     */
    public function show(mitra $mitra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\mitra  $mitra
     * @return \Illuminate\Http\Response
     */
    public function edit(mitra $mitra)
    {
        return view('mitra.edit', compact('mitra'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatemitraRequest  $request
     * @param  \App\Models\mitra  $mitra
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatemitraRequest $request, mitra $mitra)
    {
        //digunakan untuk validasi kemudian kalau ok tidak ada masalah baru diupdate ke db
        $validated = $request->validate([
            'id_mitra' => 'required',
            'nama_mitra' => 'required|max:255',
            'alamat_mitra' => 'required',
            'no_telp' => 'required',
        ]);
    
        $mitra->update($validated);
    
        return redirect()->route('mitra.index')->with('success','Data Berhasil di Ubah');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\mitra  $mitra
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //hapus dari database
        $mitra = mitra::findOrFail($id);
        $mitra->delete();

        return redirect()->route('mitra.index')->with('success','Data Berhasil di Hapus');
    }
}