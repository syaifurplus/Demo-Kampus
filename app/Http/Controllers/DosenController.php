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
            'mataKuliah' => function ($query) {
                $query->select('mata_kuliah.id', 'nama_matkul', 'sks'); // Pilih kolom dari MataKuliah
            },
            'mataKuliah.kelompok' => function ($query) {
                $query->select('id', 'id_matkul', 'nama_kelompok'); // Pilih kolom dari Kelompok
            },
            'mataKuliah.kelompok.jadwal' => function ($query) {
                $query->select('id', 'id_kelompok', 'hari', 'jam_mulai', 'jam_selesai'); // Pilih kolom dari Jadwal
            },
            'mataKuliah.kelompok.mahasiswa',
            // 'mataKuliah.kelompok.mahasiswa.nilai',
            // 'mataKuliah.kelompok.mahasiswa.absensi',
        ])->get();



        return response()->json([
            'status' => true,
            'message' => 'Daftar Dosen: Matakuliah 
                            \n Matakuliah: Kelompok
                            \n Kelompok: Jadwal, Mahasiswa
                            \n Mahasiswa: Nilai, Absensi',
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
