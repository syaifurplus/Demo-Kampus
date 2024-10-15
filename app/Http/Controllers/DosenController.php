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

        $results = DB::table('kelompok')
                    ->join('mata_kuliah', 'kelompok.id_matkul', '=', 'mata_kuliah.id')
                    ->join('dosen', 'kelompok.id_dosen', '=', 'dosen.id')
                    ->join('jadwal', 'kelompok.id', '=', 'jadwal.id_kelompok')
                    ->join('jadwal_mahasiswa', 'kelompok.id', '=', 'jadwal_mahasiswa.id_kelompok')
                    ->join('mahasiswa', 'jadwal_mahasiswa.id_mahasiswa', '=', 'mahasiswa.id')
                    ->join('nilai', function($join) {
                        $join->on('nilai.id_kelompok', '=', 'kelompok.id')
                            ->on('nilai.id_mahasiswa', '=', 'mahasiswa.id');
                    })
                    ->join('absensi', 'absensi.id_jadwal', '=', 'jadwal.id')
                    ->select(
                        'dosen.id as dosen_id', 'dosen.nama as dosen_nama', 'dosen.nip',
                        'mata_kuliah.id as matkul_id', 'mata_kuliah.nama_matkul', 'mata_kuliah.sks',
                        'kelompok.id as kelompok_id', 'kelompok.nama_kelompok',
                        'mahasiswa.id as mahasiswa_id', 'mahasiswa.nama as mahasiswa_nama', 'mahasiswa.nim',
                        'nilai.nilai_uas', 'absensi.tanggal', 'absensi.status'
                    )
                    ->get();


        $dosenData = $results->groupBy('dosen_id')->map(function ($dosenGroup) {
            // Mapping mata kuliah
            $mataKuliahData = $dosenGroup->groupBy('matkul_id')->map(function ($matkulGroup) {
                // Mapping kelompok
                $kelompokData = $matkulGroup->groupBy('kelompok_id')->map(function ($kelompokGroup) {
                    // Mapping mahasiswa
                    $mahasiswaData = $kelompokGroup->groupBy('mahasiswa_id')->map(function ($mahasiswaGroup) {
                        // Mapping nilai dan absensi untuk mahasiswa
                        $nilai = $mahasiswaGroup->map(function ($item) {
                            return [
                                'nilai_uas' => $item->nilai_uas,
                            ];
                        })->first();
        
                        $absensi = $mahasiswaGroup->map(function ($item) {
                            return [
                                'tanggal' => $item->tanggal,
                                'status'  => $item->status,
                            ];
                        });
        
                        return [
                            'id' => $mahasiswaGroup->first()->mahasiswa_id,
                            'nama' => $mahasiswaGroup->first()->mahasiswa_nama,
                            'nim' => $mahasiswaGroup->first()->nim,
                            'nilai' => [$nilai],
                            'absensi' => $absensi->toArray(),
                        ];
                    });
        
                    return [
                        'id' => $kelompokGroup->first()->kelompok_id,
                        'nama_kelompok' => $kelompokGroup->first()->nama_kelompok,
                        'mahasiswa' => $mahasiswaData->values()->toArray(),
                    ];
                });
        
                return [
                    'id' => $matkulGroup->first()->matkul_id,
                    'nama_matkul' => $matkulGroup->first()->nama_matkul,
                    'sks' => $matkulGroup->first()->sks,
                    'kelompok' => $kelompokData->values()->toArray(),
                ];
            });
        
            return [
                'id' => $dosenGroup->first()->dosen_id,
                'nama' => $dosenGroup->first()->dosen_nama,
                'nip' => $dosenGroup->first()->nip,
                'mata_kuliah' => $mataKuliahData->values()->toArray(),
            ];
        });
        
        return $dosenData->values()->toArray();
        




        return response()->json([
            'status' => true,
            'message' => 'Daftar Dosen TA 2024/2025 Ganjil: Matakuliah 
                                                Matakuliah: Kelompok
                                                Kelompok: Jadwal, Mahasiswa
                                                Mahasiswa: Nilai, Absensi',
            'data' => $results
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
