<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogBimbinganMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'log_bimbingan_mahasiswa';

    protected $fillable = [
        'id_bimbingan', 'tanggal', 'catatan',
    ];

    // Relasi dengan Bimbingan Mahasiswa
    public function bimbingan()
    {
        return $this->belongsTo(BimbinganMahasiswa::class, 'id_bimbingan');
    }
}
