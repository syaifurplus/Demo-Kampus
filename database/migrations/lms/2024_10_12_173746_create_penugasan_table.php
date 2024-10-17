<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenugasanTable extends Migration
{
    public function up()
    {
        Schema::create('penugasan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kelompok');
            $table->unsignedBigInteger('id_pertemuan');
            $table->string('nama_tugas');
            $table->text('deskripsi');
            $table->date('tenggat');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penugasan');
    }
}
