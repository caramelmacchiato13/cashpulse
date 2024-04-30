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
    protected $fillable = ['kode_akun','nama_akun','header_akun'];

    public static function getKodeCoa()
    {
        // 

    }
}
