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
            'kode_akun' => 'required|unique:coa,kode_akun',
            'nama_akun' => 'required',
            'header_akun' => 'required',
            'tipe' => 'nullable', // Ubah 'required' menjadi 'nullable'
        ]);

        COA::create($request->all());
        
        return redirect()->route('coa.index')->with('success','Data Berhasil di Input');
    }

//     public function store(StoreCOARequest $request)
// {
//     try {
//         //digunakan untuk validasi kemudian kalau ok tidak ada masalah baru disimpan ke db
//         $validated = $request->validate([
//             'kode_akun' => 'required|unique:coa,kode_akun', // tambahkan validasi unique
//             'nama_akun' => 'required',
//             'header_akun' => 'required',
//             'tipe' => 'nullable',
//         ]);

//         COA::create($request->all());
        
//         return redirect()->route('coa.index')->with('success','Data Berhasil di Input');
//     } catch (\Illuminate\Database\QueryException $e) {
//         // Tangkap error integritas database
//         if ($e->getCode() == 23000) { // kode SQL untuk integrity constraint violation
//             return redirect()->back()
//                 ->withInput()
//                 ->withErrors(['kode_akun' => 'Kode akun sudah digunakan']);
//         }
//         throw $e;
//     }
// }

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
            'kode_akun' => 'required|unique:coa,kode_akun,'.$request->id_coa,
            'nama_akun' => 'required',
            'header_akun' => 'required',
            'tipe' => 'nullable', // Ubah 'required' menjadi 'nullable'
        ]);
    
        $cOA->where('id', $request->id_coa)->update($validated);

        return redirect()->route('coa.index')->with('success','Data Berhasil di Ubah');
    
    }

//     public function update(UpdateCOARequest $request, COA $cOA)
// {
//     try {
//         //digunakan untuk validasi kemudian kalau ok tidak ada masalah baru diupdate ke db
//         $validated = $request->validate([
//             'kode_akun' => 'required|unique:coa,kode_akun,'.$request->id_coa, // tambahkan unique dengan pengecualian ID saat ini
//             'nama_akun' => 'required',
//             'header_akun' => 'required',
//             'tipe' => 'nullable',
//         ]);
    
//         $cOA->where('id', $request->id_coa)->update($validated);

//         return redirect()->route('coa.index')->with('success','Data Berhasil di Ubah');
//     } catch (\Illuminate\Database\QueryException $e) {
//         // Tangkap error integritas database
//         if ($e->getCode() == 23000) { // kode SQL untuk integrity constraint violation
//             return redirect()->back()
//                 ->withInput()
//                 ->withErrors(['kode_akun' => 'Kode akun sudah digunakan']);
//         }
//         throw $e;
//     }
// }

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

    // app/Http/Controllers/CoaController.php
// app/Http/Controllers/CoaController.php
public function getAccountsByType($type)
{
    $accounts = COA::where('tipe', $type)->orderBy('kode_akun')->get(['id', 'kode_akun', 'nama_akun']);
    return response()->json($accounts);
}

public function getAll()
    {
        try {
            $accounts = COA::all();
            return response()->json([
                'success' => true,
                'data' => $accounts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}