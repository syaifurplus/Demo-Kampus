<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenelitianTable extends Migration
{
    public function up()
    {
        Schema::create('penelitian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dosen')->constrained('dosen')->onDelete('cascade');
            $table->string('judul');
            $table->integer('tahun');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penelitian');
    }
}
