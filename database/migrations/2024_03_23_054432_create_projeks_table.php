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
            $table->string('kode_projek',5)->unique();
            $table->string('nama_projek',50);
            $table->string('jenis_projek',50);
            $table->string('nama_program',50);
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
        Schema::dropIfExists('projek');
    }
}
