<?php

namespace App\Http\Controllers;

use App\Models\COA;
use App\Http\Requests\StoreCOARequest;
use App\Http\Requests\UpdateCOARequest;

use Illuminate\Foundation\Http\FormRequest;

class COAController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //query data
        $cOA = COA::all();
        return view('coa.view',
                    [
                        'coa' => $cOA
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
        return view('COA.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCOARequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCOARequest $request)
    {
        //digunakan untuk validasi kemudian kalau ok tidak ada masalah baru disimpan ke db
        $validated = $request->validate([
            'kode_akun' => 'required',
            'nama_akun' => 'required',
            'header_akun' => 'required',
        ]);

        COA::create($request->all());
        
        return redirect()->route('coa.index')->with('success','Data Berhasil di Input');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\COA  $cOA
     * @return \Illuminate\Http\Response
     */
    public function show(COA $cOA)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\COA  $cOA
     * @return \Illuminate\Http\Response
     */
    public function edit(COA $coa)
    {
        return view('coa.edit', compact('coa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCOARequest  $request
     * @param  \App\Models\COA  $cOA
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCOARequest $request, COA $cOA)
    {
        //digunakan untuk validasi kemudian kalau ok tidak ada masalah baru diupdate ke db
        $validated = $request->validate([
            'kode_akun' => 'required',
            'nama_akun' => 'required',
            'header_akun' => 'required',
        ]);
    
        $cOA->where('id', $request->id_coa)->update($validated);

        return redirect()->route('coa.index')->with('success','Data Berhasil di Ubah');
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\COA  $cOA
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //hapus dari database
        $cOA = COA::findOrFail($id);
        $cOA->delete();

        return redirect()->route('coa.index')->with('success','Data Berhasil di Hapus');
    }
}
