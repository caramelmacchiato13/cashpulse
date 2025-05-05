<?php  
  
namespace App\Models;  
  
use Illuminate\Database\Eloquent\Factories\HasFactory;  
use Illuminate\Database\Eloquent\Model;  
use Illuminate\Support\Facades\DB;  
use Illuminate\Support\Facades\Log;

class projek extends Model  
{  
    use HasFactory;  
    protected $table = 'projek';  
    // List kolom yang bisa diisi  
    protected $fillable = ['kode_projek', 'nama_projek', 'tanggal_mulai', 'tanggal_selesai', 'besar_anggaran', 'pic_id', 'mitra_id'];  
  
    // Query nilai max dari kode projek untuk generate otomatis kode projek  
    public static function getKodeProjek()  
    {  
        // Query kode projek  
        $sql = "SELECT IFNULL(MAX(kode_projek), 'PR-000') as kode_projek   
                FROM projek";  
        $kodeprojek = DB::select($sql);  
   
        // Cacah hasilnya  
        foreach ($kodeprojek as $kdprsh) {  
            $kd = $kdprsh->kode_projek;  
        }  
   
        // Mengambil substring tiga digit akhir dari string PR-000  
        $noawal = substr($kd, -3);  
        $noakhir = $noawal + 1; // Menambahkan 1, hasilnya adalah integer cth 1  
   
        // Menyambung dengan string PR-001  
        $noakhir = 'PR-' . str_pad($noakhir, 3, "0", STR_PAD_LEFT);  
   
        // Cek apakah kode_projek sudah ada  
        while (projek::where('kode_projek', $noakhir)->exists()) {  
            $noakhir = 'PR-' . str_pad(++$noawal, 3, "0", STR_PAD_LEFT);  
        }  
   
        // Debugging: Tampilkan kode projek yang dihasilkan  
        Log::info('Generated Kode Projek: ' . $noakhir);  
   
        return $noakhir;  
    }  

    // Di Model/projek.php
    public function pic()
    {
        return $this->belongsTo(Pic::class, 'pic_id');
    }

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }
} 
