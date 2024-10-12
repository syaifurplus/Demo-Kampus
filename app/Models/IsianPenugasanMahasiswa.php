<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IsianPenugasanMahasiswa extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    
    protected $table = 'isian_penugasan_mahasiswa';

    protected $fillable = [
        'id_penugasan', 'id_mahasiswa', 'jawaban', 'nilai', 'tanggal_pengumpulan',
    ];

    // Relasi dengan Penugasan
    public function penugasan()
    {
        return $this->belongsTo(Penugasan::class, 'id_penugasan');
    }

    // Relasi dengan Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }
}
