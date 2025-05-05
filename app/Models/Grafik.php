<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Grafik extends Model
{
    use HasFactory;
        // untuk mendapatkan view grafik per Kas Masuk
        public static function kasmasuk()
        {
            $sql = "
            SELECT a.waktu,ifnull(b.total,0) as total FROM 
            v_waktu a 
            LEFT OUTER JOIN
            (
            SELECT DATE_FORMAT(tanggal_pendapatan,'%Y-%m') as waktu,
                SUM(jumlah) as total
            FROM kas_masuk
            GROUP BY DATE_FORMAT(tanggal_pendapatan,'%Y-%m')
            ) b
            ON (a.waktu=b.waktu); ";
            $hasil = DB::select($sql);
    
            return $hasil;
    
        }

        public static function kaskeluar()
        {
            $sql = "
            SELECT a.waktu,ifnull(b.total,0) as total FROM 
            v_waktu a 
            LEFT OUTER JOIN
            (
            SELECT DATE_FORMAT(tanggal,'%Y-%m') as waktu,
                SUM(jumlah) as total
            FROM kaskeluar
            GROUP BY DATE_FORMAT(tanggal,'%Y-%m')
            ) b
            ON (a.waktu=b.waktu); ";
            $hasil = DB::select($sql);
    
            return $hasil;
    
        }
}
