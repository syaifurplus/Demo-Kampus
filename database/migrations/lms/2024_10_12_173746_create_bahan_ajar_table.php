<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBahanAjarTable extends Migration
{
    public function up()
    {
        Schema::create('bahan_ajar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kelompok');
            $table->string('nama_bahan');
            $table->enum('tipe_bahan', ['Dokumen', 'Video', 'Lainnya']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bahan_ajar');
    }
}
