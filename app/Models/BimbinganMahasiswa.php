<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimbinganMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'bimbingan_mahasiswa';

    protected $fillable = [
        'id_mahasiswa', 'id_dosen', 'topik',
    ];

    // Relasi dengan Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }

    // Relasi dengan Dosen
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }

    // Relasi dengan Log Bimbingan Mahasiswa
    public function logBimbingan()
    {
        return $this->hasMany(LogBimbinganMahasiswa::class, 'id_bimbingan');
    }
}
