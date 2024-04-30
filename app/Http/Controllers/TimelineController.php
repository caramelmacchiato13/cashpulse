<?php

namespace App\Http\Controllers;

use App\Models\timeline;
use App\Http\Requests\StoretimelineRequest;
use App\Http\Requests\UpdatetimelineRequest;

use Illuminate\Foundation\Http\FormRequest;

class TimelineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //query data
        $timeline = Timeline::all();
        return view('timeline.view',
                    [
                        'timeline' => $timeline
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
        return view('timeline/create',
        [
            'id_timeline' => timeline::getIdtimeline()
        ]
      );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoretimelineRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoretimelineRequest $request)
    {
        //digunakan untuk validasi kemudian kalau ok tidak ada masalah baru disimpan ke db
        $validated = $request->validate([
            'id_timeline' => 'required',
            'tanggal_awal' => 'required',
            'tanggal_akhir' => 'required',
            'lokasi' => 'required',
        ]);

        // masukkan ke db
        Timeline::create($request->all());
        
        return redirect()->route('timeline.index')->with('success','Data Berhasil di Input');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\timeline  $timeline
     * @return \Illuminate\Http\Response
     */
    public function show(timeline $timeline)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\timeline  $timeline
     * @return \Illuminate\Http\Response
     */
    public function edit(timeline $timeline)
    {
        return view('timeline.edit', compact('timeline'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatetimelineRequest  $request
     * @param  \App\Models\timeline  $timeline
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatetimelineRequest $request, timeline $timeline)
    {
        //digunakan untuk validasi kemudian kalau ok tidak ada masalah baru diupdate ke db
        $validated = $request->validate([
            'id_timeline' => 'required',
            'tanggal_awal' => 'required',
            'tanggal_akhir' => 'required',
            'lokasi' => 'required',
        ]);
    
        $timeline->update($validated);
    
        return redirect()->route('timeline.index')->with('success','Data Berhasil di Ubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\timeline  $timeline
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //hapus dari database
        $timeline = timeline::findOrFail($id);
        $timeline->delete();

        return redirect()->route('timeline.index')->with('success','Data Berhasil di Hapus');
    }
}
