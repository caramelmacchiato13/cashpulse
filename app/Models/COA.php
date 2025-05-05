<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class COA extends Model
{
    use HasFactory;
    protected $table = 'coa';
    // list kolom yang bisa diisi
    protected $fillable = ['kode_akun','nama_akun','header_akun','tipe'];

    public static function getKodeCoa()
    {
        // 

    }

    public function kasMasuk()
    {
        return $this->hasMany(KasMasuk::class, 'coa_id');
    }
    
    public function kasKeluar()
    {
        return $this->hasMany(KasKeluar::class, 'coa_id');
    }
    

    // public static function getAccountsByType($type)
    // {
    //     return self::where('tipe', $type)
    //                 ->select('kode_akun', 'nama_akun')
    //                 ->get();
    // }

//     SELECT 
//     tipe,
//     GROUP_CONCAT(kode_akun ORDER BY kode_akun SEPARATOR ', ') AS kode_akun_list,
//     GROUP_CONCAT(nama_akun ORDER BY nama_akun SEPARATOR ', ') AS nama_akun_list
// FROM 
//     coa
// GROUP BY 
//     tipe;

}
