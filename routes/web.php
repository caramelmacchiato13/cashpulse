<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //return view('welcome');
    return redirect ('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');
// dashboardbootstrap
// Route::get('/dashboardbootstrap', function () {
//     return view('dashboardbootstrap');
// })->middleware(['auth'])->name('dashboardbootstrap');

Route::get('/dashboardbootstrap', [App\Http\Controllers\DashboardController::class, 'index']);


// route ke master data COA
Route::resource('/coa', App\Http\Controllers\COAController::class)->middleware(['auth']);
Route::get('/coa/destroy/{id}', [App\Http\Controllers\COAController::class,'destroy'])->middleware(['auth']);

// route ke master data timeline
Route::resource('/timeline', App\Http\Controllers\TimelineController::class)->middleware(['auth']);
Route::get('/timeline/destroy/{id}', [App\Http\Controllers\TimelineController::class,'destroy'])->middleware(['auth']);

//route ke master data mitra
Route::resource('/mitra', App\Http\Controllers\MitraController::class)->middleware(['auth']);
Route::get('/mitra/destroy/{id}', [App\Http\Controllers\MitraController::class,'destroy'])->middleware(['auth']);

//route ke master data jenis pendapatan
Route::resource('/jenispendapatan', App\Http\Controllers\JenisPendapatanController::class)->middleware(['auth']);
Route::get('/jenispendapatan/destroy/{id}', [App\Http\Controllers\JenisPendapatanController::class,'destroy'])->middleware(['auth']);

//route ke master data projek
Route::resource('/projek', App\Http\Controllers\ProjekController::class)->middleware(['auth']);
Route::get('/projek/destroy/{id}', [App\Http\Controllers\ProjekController::class,'destroy'])->middleware(['auth']);

//route ke master data pic
Route::resource('/pic', App\Http\Controllers\PicController::class);
Route::get('/pic/destroy/{id}', [App\Http\Controllers\PicController::class,'destroy']);

// use App\Http\Controllers\KasMasukController;
//route ke transaksi kas masuk
Route::resource('/kasmasuk', App\Http\Controllers\KasMasukController::class)->middleware(['auth']);
Route::get('/kasmasuk/destroy/{id}', [App\Http\Controllers\KasMasukController::class,'destroy'])->middleware(['auth']);
// Route::get('/kasmasuk/getAkun', [KasMasukController::class, 'getAkun'])->name('kasmasuk.getAkun');
// routes/web.php

// routes/web.php
use App\Http\Controllers\COAController;
Route::get('/get-accounts-by-type/{type}', [COAController::class, 'getAccountsByType'])->name('get.accounts.by.type')->middleware(['auth']);

//route ke transaksi kas keluar
Route::resource('/KasKeluar', App\Http\Controllers\KasKeluarController::class)->middleware(['auth']);
Route::get('/KasKeluar/destroy/{id}', [App\Http\Controllers\KasKeluarController::class,'destroy'])->middleware(['auth']);

//grafik
Route::get('grafik/kasmasuk', [App\Http\Controllers\GrafikController::class,'kasmasuk'])->middleware(['auth']);
Route::get('grafik/kaskeluar', [App\Http\Controllers\GrafikController::class,'kaskeluar'])->middleware(['auth']);


//untuk berita1
Route::get('berita', [App\Http\Controllers\Berita1Controller::class,'index'])->middleware(['auth']);
Route::get('berita1/galeri', [App\Http\Controllers\Berita1Controller::class,'getNews'])->middleware(['auth']);

// untuk berita
Route::get('berita', [App\Http\Controllers\BeritaController::class,'index'])->middleware(['auth']);
Route::get('berita/galeri', [App\Http\Controllers\BeritaController::class,'getNews'])->middleware(['auth']);

// laporan
// Route::get('jurnal/umum', [App\Http\Controllers\JurnalController::class,'jurnalumum'])->middleware(['auth']);
// Route::get('jurnal/viewdatajurnalumum/{periode}', [App\Http\Controllers\JurnalController::class,'viewdatajurnalumum'])->middleware(['auth']);
// Route::get('jurnal/bukubesar', [App\Http\Controllers\JurnalController::class,'bukubesar'])->middleware(['auth']);
// Route::get('jurnal/viewdatabukubesar/{periode}/{akun}', [App\Http\Controllers\JurnalController::class,'viewdatabukubesar'])->middleware(['auth']);


// use App\Http\Controllers\JurnalUmumController;

// // Route untuk menampilkan halaman jurnal umum
// Route::get('/jurnal/umum', [JurnalUmumController::class, 'index'])->name('jurnal.umum');
// // Route untuk mendapatkan data jurnal berdasarkan periode
// Route::post('/jurnal/umum/data', [JurnalUmumController::class, 'getData'])->name('jurnal.umum.getData');
// // Tambahkan route baru yang sesuai dengan URL di JavaScript
// Route::get('/jurnal/viewdatajurnalumum/{periode}', [JurnalUmumController::class, 'viewJurnalUmum'])->name('jurnal.viewJurnalUmum');

// Jurnal Umum
Route::get('/jurnal/umum', [App\Http\Controllers\JurnalUmumController::class, 'index'])->name('jurnal.umum');
Route::post('/jurnal/umum/data', [App\Http\Controllers\JurnalUmumController::class, 'getData'])->name('jurnal.umum.data');
Route::get('/coa/getAll', 'App\Http\Controllers\CoaController@getAll')->name('coa.getAll');

use App\Http\Controllers\BukuBesarController;
// Rute untuk Buku Besar
Route::get('bukubesar', [BukuBesarController::class, 'index'])->name('bukubesar.index');
Route::get('bukubesar/data', [BukuBesarController::class, 'getData'])->name('bukubesar.getData');
Route::get('/buku-besar', [BukuBesarController::class, 'index'])->name('bukubesar.getData');

use App\Http\Controllers\AruskasController;

// Route::get('/laporan/arus-kas', [AruskasController::class, 'index'])->name('laporan.arus-kas');
// Route::get('/laporan/arus-kas/export-pdf', [AruskasController::class, 'exportPdf'])->name('laporan.export-pdf');
// Route::get('/laporan/arus-kas/export-excel', [AruskasController::class, 'exportExcel'])->name('laporan.export-excel');

// Laporan Arus Kas
Route::prefix('laporan')->group(function () {
    Route::get('/arus-kas', [AruskasController::class, 'index'])->name('laporan.aruskas');
    Route::get('/arus-kas/export-pdf', [AruskasController::class, 'exportPDF'])->name('laporan.aruskas.export-pdf');
});
require __DIR__.'/auth.php';