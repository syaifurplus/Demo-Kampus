<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah';

    protected $fillable = [
        'kode_matkul', 'nama_matkul', 'sks',
    ];

    // Relasi dengan Kelompok
    public function kelompok()
    {
        return $this->hasMany(Kelompok::class, 'id_matkul');
    }

    // Relasi dengan Jadwal
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_matkul');
    }
}
