<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JurnalUmum extends Model
{
    use HasFactory;

    use SoftDeletes;
    
    protected $table = 'jurnal_umum';
    
    // Menggunakan fillable untuk eksplisit mengizinkan fields yang bisa di-mass assignment
    protected $fillable = [
        'id_jurnal',
        'tanggal',
        'akun_debit',
        'akun_kredit',
        'ref',
        'debet',
        'kredit',
        'keterangan',
        'projek_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    // Atau bisa menggunakan guarded jika ingin semua fields bisa di-mass assignment kecuali id
    // protected $guarded = ['id'];
    
    public function kasKeluar()
    {
        return $this->belongsTo(KasKeluar::class, 'ref_id', 'id')->where('tipe', 'keluar');
    }
    
    public function kasMasuk()
    {
        return $this->belongsTo(KasMasuk::class, 'ref_id', 'id')->where('tipe', 'masuk');
    }
    
    public function coa()
    {
        return $this->belongsTo(Coa::class, 'coa_id', 'akun', 'nama_akun');
    }

    public function projek()
    {
        return $this->belongsTo(Projek::class, 'projek_id');
    }
}