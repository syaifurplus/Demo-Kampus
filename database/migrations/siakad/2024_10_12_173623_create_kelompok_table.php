<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKelompokTable extends Migration
{
    public function up()
    {
        Schema::create('kelompok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_matkul')->constrained('mata_kuliah')->onDelete('cascade');
            $table->foreignId('id_dosen')->constrained('dosen')->onDelete('cascade');
            $table->string('nama_kelompok');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kelompok');
    }
}
