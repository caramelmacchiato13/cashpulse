<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Pembayaran extends Model
{
    use HasFactory;

    // use HasFactory;
    protected $table = "pembayaran";

    // untuk melist kolom yang dapat dimasukkan
    protected $fillable = [
        'no_transaksi',
        'tgl_bayar',
        'tgl_konfirmasi',
        'bukti_bayar',
        'jenis_pembayaran',
        'status'
    ];

    // untuk view status pembayaran berdasarkan id customer tertentu untuk PG
    public static function viewstatusPGAll()
    {
        // query kode perusahaan
        $sql = "SELECT b.*,c.status_code,c.order_id
                FROM kaskeluar b
                JOIN pg_pembayaran c
                ON (b.id=c.id_kaskeluar)
                WHERE b.status_pembayaran in ('menunggu_approve')
                ";
        $list = DB::select($sql);

        return $list;
    }
}
