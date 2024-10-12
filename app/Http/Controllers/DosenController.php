<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    /**
     * Menampilkan semua dosen beserta relasinya
     */
    public function index()
    {
        // Ambil semua dosen beserta relasinya
        $dosen = Dosen::with([
            'kelompok',             // Relasi ke Kelompok
            'kelompok.mataKuliah',   // Relasi Kelompok -> MataKuliah
            'perwalian',            // Relasi ke Perwalian
            'bimbinganMahasiswa',    // Relasi ke Bimbingan Mahasiswa
            'bimbinganKP',           // Relasi ke Bimbingan KP
            'pengabdian',            // Relasi ke Pengabdian
            'publikasi',             // Relasi ke Publikasi
            'penelitian',            // Relasi ke Penelitian
        ])->get();

        return response()->json([
            'status' => true,
            'message' => 'Daftar dosen beserta relasi-relasinya berhasil diambil',
            'data' => $dosen
        ]);
    }

    /**
     * Menampilkan satu dosen beserta relasinya berdasarkan ID
     */
    public function show($id)
    {
        // Cari dosen berdasarkan ID beserta relasinya
        $dosen = Dosen::with([
            'kelompok',             
            'kelompok.mataKuliah',  
            'perwalian',            
            'bimbinganMahasiswa',    
            'bimbinganKP',           
            'pengabdian',            
            'publikasi',             
            'penelitian',            
        ])->find($id);

        // Jika dosen tidak ditemukan
        if (!$dosen) {
            return response()->json([
                'status' => false,
                'message' => 'Dosen tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Dosen beserta relasi-relasinya berhasil diambil',
            'data' => $dosen
        ]);
    }
}
