<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublikasiTable extends Migration
{
    public function up()
    {
        Schema::create('publikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dosen')->constrained('dosen')->onDelete('cascade');
            $table->string('judul');
            $table->string('jurnal');
            $table->integer('tahun');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('publikasi');
    }
}
