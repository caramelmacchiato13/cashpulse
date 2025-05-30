<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Coa;
use Illuminate\Support\Facades\DB;

class Jurnal extends Model
{
    use HasFactory;

    // use HasFactory;
    protected $table = "jurnal";

    // untuk melist kolom yang dapat dimasukkan
    protected $fillable = [
        'id_transaksi',
        'kode_akun',
        'tgl_jurnal',
        'posisi_d_c',
        'nominal',
        'kelompok',
        'transaksi'
    ];

    // view data jurnal umum berdasarkan periode
    public static function viewjurnalumum($periode)
{
    $sql = "SELECT a.*, b.nama_akun
            FROM jurnal a 
            JOIN coa b ON (a.kode_akun = b.kode_akun)
            WHERE DATE_FORMAT(tgl_jurnal, '%Y-%m') = ?
            ORDER BY tgl_jurnal ASC";
    return DB::select($sql, [$periode]);
}

    // view data data-data akun buku besar suatu projek
    public static function viewakunbukubesar(){
        // periode memiliki format YYYY-MM
        $sql = "   SELECT b.kode_akun, b.nama_akun
                    FROM jurnal a JOIN coa b 
                    ON (a.kode_akun=b.kode_akun) 
                    GROUP BY b.kode_akun, b.nama_akun
                    ORDER BY 2 ASC
                ";
        $list = DB::select($sql);
        return $list;
    }

    // view data jurnal umum berdasarkan periode
    public static function viewdatabukubesar($periode,$akun){
        // periode memiliki format YYYY-MM
        $sql = "   SELECT a.*,b.nama_akun
                    FROM jurnal a JOIN coa b 
                    ON (a.kode_akun=b.kode_akun)
                    WHERE DATE_FORMAT(tgl_jurnal, '%Y-%m')= ?
                    AND b.kode_akun = ?
                    ORDER BY 1 ASC
                ";
        $list = DB::select($sql,[$periode,$akun]);
        return $list;
    }

    // viewposisisdb saldo normal
    public static function viewposisisaldonormalakun($akun){
        $akun = substr($akun, 0, 1); // Ambil digit pertama dari kode akun
        switch ($akun) {
            case '1': // Aset
                $posisi_saldo_normal = 'd';
                break;
            case '2': // Kewajiban
                $posisi_saldo_normal = 'c';
                break;
            case '3': // Ekuitas
                $posisi_saldo_normal = 'c';
                break;
            case '4': // Pendapatan
                $posisi_saldo_normal = 'c';
                break;
            case '5': // Beban
                $posisi_saldo_normal = 'd';
                break;
            default:
                $posisi_saldo_normal = 'd'; // Default (jika tidak sesuai)
                break;
        }
        return $posisi_saldo_normal;
    }

    public static function calculateSaldoAwal($periode, $akun) {
        // Ambil header_akun dari tabel coa
        $headerAkun = Coa::where('kode_akun', $akun)->value('header_akun');
    
        // Hitung total debet dan kredit sebelum periode yang diminta
        $saldoAwal = Jurnal::join('coa', 'jurnal.kode_akun', '=', 'coa.kode_akun')
                           ->where('jurnal.kode_akun', $akun)
                           ->whereRaw("DATE_FORMAT(jurnal.tgl_jurnal, '%Y-%m') < ?", [$periode])
                           ->selectRaw("SUM(CASE WHEN jurnal.posisi_d_c = 'd' THEN jurnal.nominal ELSE 0 END) as total_debet")
                           ->selectRaw("SUM(CASE WHEN jurnal.posisi_d_c = 'c' THEN jurnal.nominal ELSE 0 END) as total_kredit")
                           ->first();
    
        // Tentukan posisi saldo normal berdasarkan header_akun
        $posisiSaldoNormal = self::viewposisisaldonormalakun($headerAkun);
    
        // Hitung saldo awal
        if ($posisiSaldoNormal == 'd') {
            return ($saldoAwal->total_debet ?? 0) - ($saldoAwal->total_kredit ?? 0);
        } else {
            return ($saldoAwal->total_kredit ?? 0) - ($saldoAwal->total_debet ?? 0);
        }
    }

    // view saldo buku besar bulan sebelumnya berdasarkan periode dan kode akun
    public static function viewsaldobukubesar($periode,$akun){
        // dapatkan posisi saldo normal akun tsb
        $sql = "   SELECT header_akun
                    FROM coa  
                    WHERE kode_akun = ?
                ";
        $list = DB::select($sql,[$akun]);
        foreach($list as $l):
            switch ($l->header_akun) {
                case '1':
                    $posisi_saldo_normal = 'd';
                  break;
                case '2':
                    $posisi_saldo_normal = 'c';
                  break;
                case '3':
                    $posisi_saldo_normal= 'c';
                  break;
                case '4':
                    $posisi_saldo_normal = 'c';
                  break;
                case '5':
                    $posisi_saldo_normal = 'd';
                  break;
              }
        endforeach;

        $sql = "   SELECT tbl1.posisi_d_c,ifnull(tbl2.total,0) as nominal FROM
                    (
                        SELECT 'c' posisi_d_c
                        UNION
                        SELECT 'd' posisi_d_c
                    ) tbl1
                    LEFT OUTER JOIN
                    (
                        Select a.posisi_d_c,sum(a.nominal) as total
                        FROM jurnal a
                        JOIN coa b ON (a.kode_akun=b.kode_akun)
                        WHERE a.kode_akun = ? 
                        AND date_format(a.tgl_jurnal,'%Y-%m') < ?
                        GROUP BY  a.posisi_d_c
                    ) tbl2
                    ON (tbl1.posisi_d_c = tbl2.posisi_d_c)
                ";
        $list = DB::select($sql,[$akun,$periode]);
        $saldo_debet = 0;
        $saldo_kredit = 0;
        foreach($list as $cacah):
            if(strcmp($cacah->posisi_d_c,'d')==0){
                $saldo_debet = $saldo_debet + $cacah->nominal;
            }else{
                $saldo_kredit = $saldo_kredit + $cacah->nominal;
            }
        endforeach;

        if(strcmp($posisi_saldo_normal,'d')==0){
            $saldo = $saldo_debet - $saldo_kredit;
        }else{
            $saldo =  $saldo_kredit - $saldo_debet;
        }
        return $saldo;
    }

    public function kasMasuk()
    {
        return $this->belongsTo(KasMasuk::class, 'kas_masuk_id');
    }

    public function kasKeluar()
    {
        return $this->belongsTo(KasKeluar::class, 'kas_keluar_id');
    }

}