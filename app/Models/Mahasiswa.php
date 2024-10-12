<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'nama', 'nim', 'email',
    ];

    // Relasi dengan Nilai
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'id_mahasiswa');
    }

    // Relasi dengan Absensi
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_mahasiswa');
    }

    // Relasi dengan Perwalian
    public function perwalian()
    {
        return $this->hasMany(Perwalian::class, 'id_mahasiswa');
    }

    // Relasi dengan Bimbingan Mahasiswa
    public function bimbinganMahasiswa()
    {
        return $this->hasMany(BimbinganMahasiswa::class, 'id_mahasiswa');
    }

    // Relasi dengan Bimbingan KP
    public function bimbinganKP()
    {
        return $this->hasMany(BimbinganKP::class, 'id_mahasiswa');
    }

    // Relasi dengan Isian Penugasan
    public function isianPenugasanMahasiswa()
    {
        return $this->hasMany(IsianPenugasanMahasiswa::class, 'id_mahasiswa');
    }
}
