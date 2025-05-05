<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projek', function (Blueprint $table) {
            $table->id();
            $table->string('kode_projek',10)->unique();
            $table->string('nama_projek',50);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('besar_anggaran',50);
            $table->timestamps();
            $table->foreignId('pic_id')->nullable()->constrained('pic');
            $table->foreignId('mitra_id')->nullable()->constrained('mitra');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projek');
    }
}
