<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class jenis_pendapatan extends Model
{
    use HasFactory;
    protected $table = "jenis_pendapatan";
    protected $fillable = ['kode_jenis_pendapatan','sumber_pendapatan','tanggal_pendapatan'];

    public static function getKodeJenisPendapatan()
    {
        $sql = "SELECT IFNULL(MAX(kode_jenis_pendapatan), 'PDP-000') as kode_jenis_pendapatan
        FROM jenis_pendapatan";
        $kode_jenis_pendapatan = DB::select($sql);

        foreach ($kode_jenis_pendapatan as $kdpdpt) {
            $kd = $kdpdpt->kode_jenis_pendapatan;
        }

        $noawal = substr($kd,-3);
        $noakhir = $noawal+1;

        $noakhir = 'PDP-'.str_pad($noakhir,3,"0",STR_PAD_LEFT); 

        return $noakhir;
    }
}