<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class pic extends Model
{
    use HasFactory;
    protected $table = 'pic';
    // list kolom yang bisa diisi
    protected $fillable = ['kode_pic','nama_pic','email', 'no_telp'];

    // query nilai max dari kode pic untuk generate otomatis kode pic
    public static function getKodePic()
    {
        // query kode pic
        $sql = "SELECT IFNULL(MAX(kode_pic), 'PIC-000') as kode_pic 
                FROM pic";
        $kodepic = DB::select($sql);

        // cacah hasilnya
        foreach ($kodepic as $kdprsh) {
            $kd = $kdprsh->kode_pic;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $noawal = substr($kd,-3);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        
        //menyambung dengan string PR-001
        $noakhir = 'PIC-'.str_pad($noakhir,3,"0",STR_PAD_LEFT); 

        return $noakhir;

    }
}
