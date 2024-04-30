<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class mitra extends Model
{
    use HasFactory;
    protected $table = 'mitra';
    // list kolom yang bisa diisi
    protected $fillable = ['id_mitra','nama_mitra','alamat_mitra','no_telp'];

    
    public static function getKodemitra()
    {
        
        $sql = "SELECT IFNULL(MAX(id_mitra), 'MTR-000') as id_mitra 
                FROM mitra";
        $kodemitra = DB::select($sql);

        // cacah hasilnya
        foreach ($kodemitra as $idmtr) {
            $kd = $idmtr->id_mitra;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $noawal = substr($kd,-3);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        
        //menyambung dengan string PR-001
        $noakhir = 'MTR-'.str_pad($noakhir,3,"0",STR_PAD_LEFT); 

        return $noakhir;

    }
}