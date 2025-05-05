<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class KasKeluar extends Model
{
    use HasFactory;
    protected $table = 'kaskeluar';
    
    protected $fillable = [  
        'no_kaskeluar', // Menambahkan kolom no_kaskeluar  
        'tanggal',  
        'keterangan',  
        'tipe',  
        'jumlah',  
        'projek_id', 
        'coa_id', 
        'evidence',
        'original_filename'
    ];

    public static function generateNoKasKeluar()  
    {  
    $lastKasKeluar = self::orderBy('id', 'desc')->first();  
    if ($lastKasKeluar) {  
        $lastNo = (int) substr($lastKasKeluar->no_kaskeluar, 3); // Mengambil angka dari no_kaskeluar  
        $newNo = $lastNo + 1;  
    } else {  
        $newNo = 1; // Jika tidak ada data, mulai dari 1  
    }  
    return 'KK-' . str_pad($newNo, 3, '0', STR_PAD_LEFT); // Format KK-001  
    }  


    // Definisikan relasi dengan model Projek  
    public function projek()    
    {    
        return $this->belongsTo(projek::class, 'projek_id');    
    }  

    public function coa() 
    {    
        return $this->belongsTo(coa::class, 'coa_id');    
    }
//     public function projek()
// {
//     return $this->belongsTo(Projek::class, 'projek_id');
// }

    public function jurnal()
    {
        return $this->hasOne(Jurnal::class, 'kas_keluar_id');
    }
}