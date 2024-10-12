<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalTable extends Migration
{
    public function up()
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_matkul')->constrained('mata_kuliah')->onDelete('cascade');
            $table->foreignId('id_dosen')->constrained('dosen')->onDelete('cascade');
            $table->foreignId('id_kelompok')->constrained('kelompok')->onDelete('cascade');
            $table->string('hari');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruang');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal');
    }
}
