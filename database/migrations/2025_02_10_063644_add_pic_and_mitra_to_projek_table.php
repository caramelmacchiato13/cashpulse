<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPicAndMitraToProjekTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projek', function (Blueprint $table) {
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
        Schema::table('projek', function (Blueprint $table) {
            //
        });
    }
}
