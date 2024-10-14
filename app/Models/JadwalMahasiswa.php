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

    // Relasi ke Kelompok
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'id_kelompok');
    }
}
