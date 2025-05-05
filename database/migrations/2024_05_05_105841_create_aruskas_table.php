<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAruskasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aruskas', function (Blueprint $table) {
            $table->id();
            $table->date('periode');
            $table->decimal('kas_awal', 15, 2);
            $table->decimal('kas_akhir', 15, 2);
            $table->timestamps();
            $table->unsignedBigInteger('projek_id')->nullable()->after('id');
        $table->foreign('projek_id')->references('id')->on('projeks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aruskas');
    }
}
