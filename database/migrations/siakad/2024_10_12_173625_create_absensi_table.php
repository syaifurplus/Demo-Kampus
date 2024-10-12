<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsensiTable extends Migration
{
    public function up()
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_mahasiswa')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignId('id_jadwal')->constrained('jadwal')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('status', ['Hadir', 'Tidak Hadir']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('absensi');
    }
}
