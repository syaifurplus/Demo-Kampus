<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanAjar extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    
    protected $table = 'bahan_ajar';

    protected $fillable = [
        'id_kelompok', 'nama_bahan', 'tipe_bahan',
    ];

    // Relasi dengan Kelompok
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'id_kelompok');
    }
}
