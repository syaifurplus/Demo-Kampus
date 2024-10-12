<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompok';

    protected $fillable = [
        'id_matkul', 'id_dosen', 'nama_kelompok',
    ];

    // Relasi dengan MataKuliah
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'id_matkul');
    }

    // Relasi dengan Dosen
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }

    // Relasi dengan Jadwal
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_kelompok');
    }

    // Relasi dengan Nilai
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'id_kelompok');
    }

    // Relasi dengan Bahan Ajar
    public function bahanAjar()
    {
        return $this->hasMany(BahanAjar::class, 'id_kelompok');
    }

    // Relasi dengan Penugasan
    public function penugasan()
    {
        return $this->hasMany(Penugasan::class, 'id_kelompok');
    }
}
