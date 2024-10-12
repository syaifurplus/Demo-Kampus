<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengabdianTable extends Migration
{
    public function up()
    {
        Schema::create('pengabdian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dosen')->constrained('dosen')->onDelete('cascade');
            $table->string('judul');
            $table->integer('tahun');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengabdian');
    }
}
