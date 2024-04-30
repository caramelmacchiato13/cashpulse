<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class projek extends Model
{
    use HasFactory;
    protected $table = 'projek';
    // list kolom yang bisa diisi
    protected $fillable = ['kode_projek','nama_projek','jenis_projek', 'nama_program'];

    // query nilai max dari kode projek untuk generate otomatis kode projek
    public static function getKodeProjek()
    {
        // query kode projek
        $sql = "SELECT IFNULL(MAX(kode_projek), 'PR-000') as kode_projek 
                FROM projek";
        $kodeprojek = DB::select($sql);

        // cacah hasilnya
        foreach ($kodeprojek as $kdprsh) {
            $kd = $kdprsh->kode_projek;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $noawal = substr($kd,-3);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        
        //menyambung dengan string PR-001
        $noakhir = 'PR-'.str_pad($noakhir,3,"0",STR_PAD_LEFT); 

        return $noakhir;

    }
}

