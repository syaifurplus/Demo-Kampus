<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';

    protected $fillable = [
        'nama', 'nip', 'email',
    ];

    // Relasi dengan Kelompok
    public function kelompok()
    {
        return $this->hasMany(Kelompok::class, 'id_dosen');
    }

    // Relasi dengan Perwalian
    public function perwalian()
    {
        return $this->hasMany(Perwalian::class, 'id_dosen');
    }

    // Relasi dengan Bimbingan Mahasiswa
    public function bimbinganMahasiswa()
    {
        return $this->hasMany(BimbinganMahasiswa::class, 'id_dosen');
    }

    // Relasi dengan Bimbingan KP
    public function bimbinganKP()
    {
        return $this->hasMany(BimbinganKP::class, 'id_dosen');
    }

    // Relasi dengan Pengabdian
    public function pengabdian()
    {
        return $this->hasMany(Pengabdian::class, 'id_dosen');
    }

    // Relasi dengan Publikasi
    public function publikasi()
    {
        return $this->hasMany(Publikasi::class, 'id_dosen');
    }

    // Relasi dengan Penelitian
    public function penelitian()
    {
        return $this->hasMany(Penelitian::class, 'id_dosen');
    }
}
