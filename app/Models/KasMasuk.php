<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasMasuk extends Model
{
    protected $table = 'kas_masuk';

    protected $fillable = [
        'no_kasmasuk', 
        'tanggal', 
        'keterangan', 
        'tipe', 
        'jumlah', 
        'projek_id', 
        'coa_id', 
        'evidence', 
        'original_filename'
    ];

    public function projek()
    {
        return $this->belongsTo(Projek::class, 'projek_id');
    }

    public function coa()
    {
        return $this->belongsTo(COA::class, 'coa_id');
    }

    public static function generateNoKasMasuk()
    {
        $lastKasMasuk = self::orderBy('id', 'desc')->first();
        if ($lastKasMasuk) {
            $lastNo = (int) substr($lastKasMasuk->no_kasmasuk, 3);
            $newNo = $lastNo + 1;
        } else {
            $newNo = 1;
        }
        return 'KM-' . str_pad($newNo, 3, '0', STR_PAD_LEFT);
    }
}
