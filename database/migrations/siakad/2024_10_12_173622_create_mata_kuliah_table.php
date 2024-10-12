<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMataKuliahTable extends Migration
{
    public function up()
    {
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->string('kode_matkul');
            $table->string('nama_matkul');
            $table->integer('sks');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mata_kuliah');
    }
}
