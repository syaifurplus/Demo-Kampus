<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimbinganKP extends Model
{
    use HasFactory;

    protected $table = 'bimbingan_kp';

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

    // Relasi dengan Log Bimbingan KP
    public function logBimbingan()
    {
        return $this->hasMany(LogBimbinganKP::class, 'id_bimbingan');
    }
}
