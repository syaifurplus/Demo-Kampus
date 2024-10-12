<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerwalianTable extends Migration
{
    public function up()
    {
        Schema::create('perwalian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dosen')->constrained('dosen')->onDelete('cascade');
            $table->foreignId('id_mahasiswa')->constrained('mahasiswa')->onDelete('cascade');
            $table->enum('status_validasi', ['Validasi', 'Belum Validasi']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('perwalian');
    }
}
