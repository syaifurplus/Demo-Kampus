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
    
    public function mataKuliah()
    {
        return $this->hasManyThrough(
            MataKuliah::class,         // Model yang dituju
            Kelompok::class,           // Model perantara
            'id_dosen',                // Foreign key di tabel kelompok
            'id',                      // Foreign key di tabel mata kuliah
            'id',                      // Primary key di dosen
            'id_matkul'                // Foreign key di kelompok yang merujuk ke mata kuliah
        );
    }

}
