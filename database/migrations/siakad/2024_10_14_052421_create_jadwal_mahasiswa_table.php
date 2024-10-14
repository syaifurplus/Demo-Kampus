<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalMahasiswaTable extends Migration
{
    public function up()
    {
        Schema::create('jadwal_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_mahasiswa')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignId('id_jadwal')->constrained('jadwal')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal_mahasiswa');
    }
}
