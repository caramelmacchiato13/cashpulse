<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisPendapatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_pendapatan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jenis_pendapatan',7)->unique();
            $table->string('sumber_pendapatan',50);
            $table->date('tanggal_pendapatan',5);
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
        Schema::dropIfExists('jenis_pendapatan');
    }
}
