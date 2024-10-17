<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogBimbinganKPTable extends Migration
{
    public function up()
    {
        Schema::create('log_bimbingan_kp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_bimbingan_kp')->constrained('bimbingan_kp')->onDelete('cascade');
            $table->date('tanggal');
            $table->text('catatan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_bimbingan_kp');
    }
}
