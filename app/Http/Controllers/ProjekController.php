<?php

namespace App\Http\Controllers;

use App\Models\projek;
use App\Http\Requests\StoreprojekRequest;
use App\Http\Requests\UpdateprojekRequest;

class ProjekController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //querry data
        $projek = projek::all();
        return view('projek.view',
                    [
                        'projek' => $projek
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
        //berikan kode projek secara otomatis
        // 1. query dulu ke db, select max untuk mengetahui posisi terakhir 
        
        return view('projek/create',
        [
            'kode_projek' => projek::getKodeProjek()
        ]
      );
// return view('projek/view');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreprojekRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreprojekRequest $request)
    {
        //digunakan untuk validasi kemudian kalau ok tidak ada masalah baru disimpan ke db
        $validated = $request->validate([
            'kode_projek' => 'required',
            'nama_projek' => 'required|unique:projek|min:5|max:255',
            'jenis_projek' => 'required',
            'nama_program' => 'required',
        ]);

        // masukkan ke db
        Projek::create($request->all());
        
        return redirect()->route('projek.index')->with('success','Data Berhasil di Input');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\projek  $projek
     * @return \Illuminate\Http\Response
     */
    public function show(projek $projek)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\projek  $projek
     * @return \Illuminate\Http\Response
     */
    public function edit(projek $projek)
    {
        return view('projek.edit', compact('projek'));
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateprojekRequest  $request
     * @param  \App\Models\projek  $projek
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateprojekRequest $request, projek $projek)
    {
        //digunakan untuk validasi kemudian kalau ok tidak ada masalah baru diupdate ke db
        $validated = $request->validate([
            'kode_projek' => 'required',
            'nama_projek' => 'required|max:255',
            'jenis_projek' => 'required',
            'nama_program' => 'required',
        ]);
    
        $projek->update($validated);
        
        return redirect()->route('projek.index')->with('success','Data Berhasil di Ubah');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\projek  $projek
     * @return \Illuminate\Http\Response
     */
    public function destroy(projek $id)
    {
        // //hapus dari database
        $projek = projek::findOrFail($id);
        $projek->delete();

        return redirect()->route('projek.index')->with('success','Data Berhasil di Hapus');
    }
}
    
