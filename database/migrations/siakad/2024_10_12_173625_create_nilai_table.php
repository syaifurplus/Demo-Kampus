<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiTable extends Migration
{
    public function up()
    {
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_mahasiswa')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignId('id_kelompok')->constrained('kelompok')->onDelete('cascade');
            $table->decimal('nilai_uts', 5, 2);
            $table->decimal('nilai_uas', 5, 2);
            $table->decimal('nilai_tugas_akhir', 5, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nilai');
    }
}
