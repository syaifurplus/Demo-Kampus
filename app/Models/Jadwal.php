<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'id_matkul', 'id_dosen', 'id_kelompok', 'hari', 'jam_mulai', 'jam_selesai', 'ruang',
    ];

    // Relasi ke Kelompok
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'id_kelompok');
    }

    // Relasi ke Mahasiswa melalui jadwal_mahasiswa
    public function mahasiswa()
    {
        return $this->belongsToMany(Mahasiswa::class, 'jadwal_mahasiswa', 'id_jadwal', 'id_mahasiswa');
    }
}
