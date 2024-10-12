<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogBimbinganKP extends Model
{
    use HasFactory;

    protected $table = 'log_bimbingan_kp';

    protected $fillable = [
        'id_bimbingan', 'tanggal', 'catatan',
    ];

    // Relasi dengan Bimbingan KP
    public function bimbingan()
    {
        return $this->belongsTo(BimbinganKP::class, 'id_bimbingan');
    }
}
