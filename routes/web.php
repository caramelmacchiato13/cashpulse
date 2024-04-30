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

// dashboardbootstrap
Route::get('/dashboardbootstrap', function () {
    return view('dashboardbootstrap');
})->middleware(['auth'])->name('dashboardbootstrap');

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
require __DIR__.'/auth.php';
