<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DosenController extends Controller
{
    /**
     * Menampilkan semua dosen beserta relasinya
     */
    public function index()
    {

        $dosen = DB::table('dosen')
            ->select(
                'dosen.id as dosen_id',
                'dosen.nama as dosen_nama',
                'dosen.nip',
                'dosen.email',
                'mata_kuliah.id as mata_kuliah_id',
                'mata_kuliah.nama_matkul',
                'mata_kuliah.sks',
                'kelompok.id as kelompok_id',
                'kelompok.nama_kelompok',
                'jadwal.id as jadwal_id',
                'jadwal.hari',
                'jadwal.jam_mulai',
                'jadwal.jam_selesai',
                'mahasiswa.id as mahasiswa_id',
                'mahasiswa.nama as mahasiswa_nama',
                'mahasiswa.nim',
                // 'nilai.id_kelompok',
                // 'nilai.id_mahasiswa',
                'nilai.id as nilai_id',
                'nilai.nilai_uts',
                'nilai.nilai_uas',
                'nilai.nilai_tugas_akhir'
            )
            ->join('jadwal', 'jadwal.id_dosen', '=', 'dosen.id') // Join antara dosen dan jadwal
            ->join('kelompok', 'kelompok.id', '=', 'jadwal.id_kelompok') // Join kelompok dengan jadwal
            ->join('mata_kuliah', 'mata_kuliah.id', '=', 'kelompok.id_matkul') // Join mata kuliah dengan kelompok
            ->join('jadwal_mahasiswa', 'jadwal_mahasiswa.id_kelompok', '=', 'kelompok.id') // Join jadwal_mahasiswa dan kelompok
            ->join('mahasiswa', 'mahasiswa.id', '=', 'jadwal_mahasiswa.id_mahasiswa') // Join mahasiswa dengan jadwal_mahasiswa
            ->leftJoin('nilai', function ($join) {
                // Join nilai berdasarkan mahasiswa dan kelompok
                $join->on('nilai.id_mahasiswa', '=', 'mahasiswa.id')
                    ->on('nilai.id_kelompok', '=', 'kelompok.id');
            })
            ->get()
            ->groupBy('dosen_id') // Mengelompokkan data berdasarkan dosen
            ->map(function ($items) {
                return [
                    'id' => $items->first()->dosen_id,
                    'nama' => $items->first()->dosen_nama,
                    'nip' => $items->first()->nip,
                    'email' => $items->first()->email,
                    'mata_kuliah' => collect($items)->groupBy('mata_kuliah_id')->map(function ($mataKuliahItems) {
                        return [
                            'id' => $mataKuliahItems->first()->mata_kuliah_id,
                            'nama_matkul' => $mataKuliahItems->first()->nama_matkul,
                            'sks' => $mataKuliahItems->first()->sks,
                            'kelompok' => collect($mataKuliahItems)->groupBy('kelompok_id')->map(function ($kelompokItems) {
                                return [
                                    'id' => $kelompokItems->first()->kelompok_id,
                                    'nama_kelompok' => $kelompokItems->first()->nama_kelompok,
                                    'jadwal' => collect($kelompokItems)->unique('jadwal_id')->map(function ($jadwalItems) {
                                        return [
                                            'id' => $jadwalItems->jadwal_id,
                                            'hari' => $jadwalItems->hari,
                                            'jam_mulai' => $jadwalItems->jam_mulai,
                                            'jam_selesai' => $jadwalItems->jam_selesai
                                        ];
                                    })->values(),
                                    'mahasiswa' => collect($kelompokItems)->groupBy('mahasiswa_id')->map(function ($mahasiswaItems) {
                                        return [
                                            'id' => $mahasiswaItems->first()->mahasiswa_id,
                                            'nama' => $mahasiswaItems->first()->mahasiswa_nama,
                                            'nim' => $mahasiswaItems->first()->nim,
                                            'nilai' => collect($mahasiswaItems)->map(function ($nilaiItems) {
                                                return [
                                                    // 'id_kelompok' => $nilaiItems->id_kelompok,
                                                    // 'id_mahasiswa' => $nilaiItems->id_mahasiswa,
                                                    'id' => $nilaiItems->nilai_id,
                                                    'nilai_uts' => $nilaiItems->nilai_uts,
                                                    'nilai_uas' => $nilaiItems->nilai_uas,
                                                    'nilai_tugas_akhir' => $nilaiItems->nilai_tugas_akhir
                                                ];
                                            })->values()
                                        ];
                                    })->values(),
                                ];
                            })->values(),
                        ];
                    })->values()
                ];
            })->values();



        return response()->json([
            'status' => true,
            'message' => 'Daftar Dosen TA 2024/2025 Ganjil: Matakuliah 
                                                Matakuliah: Kelompok
                                                Kelompok: Jadwal, Mahasiswa
                                                Mahasiswa: Nilai, Absensi',
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
