<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';

    // Relasi ke Nilai
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'id_mahasiswa');
    }

    // Relasi ke Absensi
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_mahasiswa');
    }
}
