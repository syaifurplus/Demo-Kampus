<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penugasan extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql2';

    protected $table = 'penugasan';

    protected $fillable = [
        'id_kelompok', 'nama_tugas', 'deskripsi', 'tenggat',
    ];

    // Relasi dengan Kelompok
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'id_kelompok');
    }

    // Relasi dengan Isian Penugasan Mahasiswa
    public function isianPenugasan()
    {
        return $this->hasMany(IsianPenugasanMahasiswa::class, 'id_penugasan');
    }
}
