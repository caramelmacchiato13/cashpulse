<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class timeline extends Model
{
    use HasFactory;
    protected $table = 'timeline';
    // list kolom yang bisa diisi
    protected $fillable = ['id_timeline','tanggal_awal','tanggal_akhir','lokasi'];

    // query nilai max dari id timeline untuk generate otomatis id timeline
    public static function getIdTimeline()
    {
        // query id timeline
        $sql = "SELECT IFNULL(MAX(id_timeline), 'TM000') as id_timeline 
                FROM timeline";
        $idtimeline = DB::select($sql);

        // cacah hasilnya
        foreach ($idtimeline as $kdprsh) {
            $kd = $kdprsh->id_timeline;
        }
        // Mengambil substring tiga digit akhir dari string PR-000
        $noawal = substr($kd,-3);
        $noakhir = $noawal+1; //menambahkan 1, hasilnya adalah integer cth 1
        
        //menyambung dengan string PR-001
        $noakhir = 'TM'.str_pad($noakhir,3,"0",STR_PAD_LEFT); 

        return $noakhir;

    }
}
