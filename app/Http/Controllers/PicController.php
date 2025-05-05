<?php

namespace App\Http\Controllers;

use App\Models\pic;
use App\Http\Requests\StorepicRequest;
use App\Http\Requests\UpdatepicRequest;

class PicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //query data
        $pic = pic::all();
        return view('pic.view',
                    [
                        'pic' => $pic
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
       
        
        return view('pic/create',
                    [
                        'kode_pic' => pic::getKodePic()
                    ]
                  );
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorepicRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorepicRequest $request)
    {
        //digunakan untuk validasi kemudian kalau ok tidak ada masalah baru disimpan ke db
        $validated = $request->validate([
            'kode_pic' => 'required',
            'nama_pic' => 'required|unique:pic|min:5|max:255',
            'email' => 'required',
            'no_telp' => 'required',
            
        ]);

        // masukkan ke db
        pic::create($request->all());
        
        return redirect()->route('pic.index')->with('success','Data Berhasil di Input');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\pic  $pic
     * @return \Illuminate\Http\Response
     */
    public function show(pic $pic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\pic  $pic
     * @return \Illuminate\Http\Response
     */
    public function edit(pic $pic)
    {
        return view('pic.edit', compact('pic'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatepicRequest  $request
     * @param  \App\Models\pic  $pic
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatepicRequest $request, pic $pic)
    {
        //digunakan untuk validasi kemudian kalau ok tidak ada masalah baru diupdate ke db
        $validated = $request->validate([
            'kode_pic' => 'required',
            'nama_pic' => 'required|max:255',
            'email' => 'required',
            'no_telp' => 'required',
        ]);
    
        $pic->update($validated);
    
        return redirect()->route('pic.index')->with('success','Data Berhasil di Ubah');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\pic  $pic
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //hapus dari database
        $pic = pic::findOrFail($id);
        $pic->delete();

        return redirect()->route('pic.index')->with('success','Data Berhasil di Hapus');
    }
}