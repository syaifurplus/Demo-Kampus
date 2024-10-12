<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBimbinganMahasiswaTable extends Migration
{
    public function up()
    {
        Schema::create('bimbingan_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_mahasiswa')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignId('id_dosen')->constrained('dosen')->onDelete('cascade');
            $table->string('topik');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bimbingan_mahasiswa');
    }
}
