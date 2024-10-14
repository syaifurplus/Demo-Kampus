<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompok';

    // Relasi ke MataKuliah
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'id_matkul');
    }

    // Relasi ke Jadwal
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_kelompok');
    }

    public function mahasiswa()
    {
        return $this->hasMany(JadwalMahasiswa::class, 'id_mahasiswa');
    }
}
