<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKasMasuksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kas_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('no_kasmasuk',10)->unique();
            $table->date('tanggal');
            $table->string('keterangan',50);
            $table->string('tipe',50);
            $table->integer('jumlah');
            $table->foreignId('projek_id')->constrained('projek')->onDelete('cascade');
            $table->foreignId('coa_id')->constrained('coa')->onDelete('cascade');
            $table->string('evidence')->nullable(); // Menambahkan kolom evidence
            $table->string('original_filename')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kas_masuk');
    }
}