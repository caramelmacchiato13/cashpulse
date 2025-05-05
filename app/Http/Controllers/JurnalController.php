<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Http\Requests\StoreJurnalRequest;
use App\Http\Requests\UpdateJurnalRequest;
use App\Models\Coa;
class JurnalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreJurnalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreJurnalRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Jurnal  $jurnal
     * @return \Illuminate\Http\Response
     */
    public function show(Jurnal $jurnal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Jurnal  $jurnal
     * @return \Illuminate\Http\Response
     */
    public function edit(Jurnal $jurnal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateJurnalRequest  $request
     * @param  \App\Models\Jurnal  $jurnal
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateJurnalRequest $request, Jurnal $jurnal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Jurnal  $jurnal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Jurnal $jurnal)
    {
        //
    }

    // jurnal umum
    public function jurnalumum(){
        return view('laporan/jurnalumum');
    }

    public function viewdatajurnalumum($periode)
{
    // Query data jurnal berdasarkan periode
    $jurnal = Jurnal::viewjurnalumum($periode);
    if ($jurnal) {
        return response()->json([
            'status' => 200,
            'jurnal' => $jurnal,
        ]);
    } else {
        return response()->json([
            'status' => 404,
            'message' => 'Tidak ada data ditemukan.'
        ]);
    }
}


    // buku besar
    public function bukubesar(){
        $akun = Jurnal::viewakunbukubesar();
        return view('laporan/bukubesar',
                        [
                            'akun' => $akun
                        ]
                    );
    }

    // view data buku besar
    public function viewdatabukubesar($periode, $akun) {
        // Validasi format periode (YYYY-MM)
        if (!preg_match('/^\d{4}-\d{2}$/', $periode)) {
            return response()->json([
                'status' => 400,
                'message' => 'Format periode tidak valid.'
            ], 400);
        }
    
        // Validasi akun
        if (!Coa::where('kode_akun', $akun)->exists()) {
            return response()->json([
                'status' => 404,
                'message' => 'Akun tidak ditemukan.'
            ], 404);
        }
    
        // Hitung saldo awal
        $saldoAwal = Jurnal::calculateSaldoAwal($periode, $akun);
    
        // Ambil posisi saldo normal
        $headerAkun = Coa::where('kode_akun', $akun)->value('header_akun');
        $posisiSaldoNormal = Jurnal::viewposisisaldonormalakun($headerAkun);
    
        // Ambil data buku besar
        $bukubesar = Jurnal::viewdatabukubesar($periode, $akun);
    
        return response()->json([
            'status' => 200,
            'bukubesar' => $bukubesar,
            'saldoawal' => $saldoAwal,
            'posisi' => $posisiSaldoNormal
        ]);
    }
}