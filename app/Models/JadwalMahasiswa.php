<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'jadwal_mahasiswa';

    protected $fillable = [
        'id_mahasiswa',
        'id_jadwal',
    ];

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }
}
