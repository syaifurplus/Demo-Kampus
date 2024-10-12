<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogBimbinganMahasiswaTable extends Migration
{
    public function up()
    {
        Schema::create('log_bimbingan_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_bimbingan')->constrained('bimbingan_mahasiswa')->onDelete('cascade');
            $table->date('tanggal');
            $table->text('catatan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_bimbingan_mahasiswa');
    }
}
