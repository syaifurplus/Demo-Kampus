<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePertemuanTable extends Migration
{
    public function up()
    {
        Schema::create('pertemuan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kelompok');
            $table->string('minggu');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pertemuan');
    }
}
