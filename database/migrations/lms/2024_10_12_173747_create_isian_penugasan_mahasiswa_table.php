<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIsianPenugasanMahasiswaTable extends Migration
{
    public function up()
    {
        Schema::create('isian_penugasan_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pertemuan');
            $table->unsignedBigInteger('id_penugasan');
            $table->unsignedBigInteger('id_mahasiswa');
            $table->text('jawaban');
            $table->decimal('nilai', 5, 2);
            $table->date('tanggal_pengumpulan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('isian_penugasan_mahasiswa');
    }
}
