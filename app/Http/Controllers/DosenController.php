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
                    ->join('absensi', function($join) {
                        $join->on('absensi.id_jadwal', '=', 'jadwal.id')
                            ->on('absensi.id_mahasiswa', '=', 'mahasiswa.id');
                    })
                    // Join Publikasi, Penelitian, dan Pengabdian
                    ->leftJoin('publikasi', 'publikasi.id_dosen', '=', 'dosen.id')
                    ->leftJoin('penelitian', 'penelitian.id_dosen', '=', 'dosen.id')
                    ->leftJoin('pengabdian', 'pengabdian.id_dosen', '=', 'dosen.id')
                    ->select(
                        'dosen.id as dosen_id', 'dosen.nama as dosen_nama', 'dosen.nip',
                        'mata_kuliah.id as matkul_id', 'mata_kuliah.nama_matkul', 'mata_kuliah.sks',
                        'kelompok.id as kelompok_id', 'kelompok.nama_kelompok',
                        'mahasiswa.id as mahasiswa_id', 'mahasiswa.nama as mahasiswa_nama', 'mahasiswa.nim',
                        'nilai.nilai_tugas_akhir', 'nilai.nilai_uts', 'nilai.nilai_uas', 
                        'absensi.tanggal', 'absensi.status',
                        'publikasi.judul as publikasi_judul', 'publikasi.tahun as publikasi_tahun', 'publikasi.jurnal as publikasi_jurnal',
                        'penelitian.judul as penelitian_judul', 'penelitian.tahun as penelitian_tahun',
                        'pengabdian.judul as pengabdian_judul', 'pengabdian.tahun as pengabdian_tahun'
                    )
                    ->get();



        $dosenData = $results->groupBy('dosen_id')->map(function ($dosenGroup) {
            // Ambil publikasi beserta tahun
            $publikasi = $dosenGroup->map(function ($item) {
                return [
                    'judul' => $item->publikasi_judul,
                    'jurnal' => $item->publikasi_jurnal,
                    'tahun' => $item->publikasi_tahun,
                ];
            })->unique()->filter()->values();
        
            // Ambil penelitian beserta tahun
            $penelitian = $dosenGroup->map(function ($item) {
                return [
                    'judul' => $item->penelitian_judul,
                    'tahun' => $item->penelitian_tahun,
                ];
            })->unique()->filter()->values();
        
            // Ambil pengabdian beserta tahun
            $pengabdian = $dosenGroup->map(function ($item) {
                return [
                    'judul' => $item->pengabdian_judul,
                    'tahun' => $item->pengabdian_tahun,
                ];
            })->unique()->filter()->values();
        
            // Mapping mata kuliah
            $mataKuliahData = $dosenGroup->groupBy('matkul_id')->map(function ($matkulGroup) {
                // Mapping kelompok
                $kelompokData = $matkulGroup->groupBy('kelompok_id')->map(function ($kelompokGroup) {
                    // Mapping mahasiswa
                    $mahasiswaData = $kelompokGroup->groupBy('mahasiswa_id')->map(function ($mahasiswaGroup) {
                        // Mapping nilai dan absensi untuk mahasiswa
                        $nilai = $mahasiswaGroup->map(function ($item) {
                            return [
                                'nilai_tugas_akhir' => $item->nilai_tugas_akhir,
                                'nilai_uts' => $item->nilai_uts,
                                'nilai_uas' => $item->nilai_uas,
                            ];
                        })->first();
        
                        $absensi = $mahasiswaGroup->map(function ($item) {
                            return [
                                'tanggal' => $item->tanggal,
                                'status'  => $item->status,
                            ];
                        })->unique('tanggal')->sortBy('tanggal'); // Pastikan absensi unik dan terurut
        
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
                'publikasi' => $publikasi->toArray(),
                'penelitian' => $penelitian->toArray(),
                'pengabdian' => $pengabdian->toArray(),
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

    public function penelitianPengabdian(){
        $results = DB::table('dosen as d')
            ->leftJoin('pengabdian as p', 'p.id_dosen', '=', 'd.id')
            ->leftJoin('publikasi as pub', 'pub.id_dosen', '=', 'd.id')
            ->leftJoin('penelitian as pen', 'pen.id_dosen', '=', 'd.id')
            ->select(
                'd.id as dosen_id', 
                'd.nama as dosen_nama', 
                'd.nip',
                'p.judul as pengabdian_judul', 
                'p.tahun as pengabdian_tahun',
                'pub.judul as publikasi_judul', 
                'pub.jurnal as publikasi_jurnal', 
                'pub.tahun as publikasi_tahun',
                'pen.judul as penelitian_judul', 
                'pen.tahun as penelitian_tahun'
            )
            ->orderBy('d.id')
            ->get();

        // Format hasil ke dalam bentuk JSON yang diinginkan
        $dosenData = $results->groupBy('dosen_id')->map(function ($dosenGroup) {
            return [
                'dosen_id' => $dosenGroup->first()->dosen_id,
                'nama' => $dosenGroup->first()->dosen_nama,
                'nip' => $dosenGroup->first()->nip,
                'pengabdian' => $dosenGroup->filter(function ($item) {
                    return !is_null($item->pengabdian_judul);
                })->map(function ($item) {
                    return [
                        'judul' => $item->pengabdian_judul,
                        'tahun' => $item->pengabdian_tahun,
                    ];
                })->values(),
                'publikasi' => $dosenGroup->filter(function ($item) {
                    return !is_null($item->publikasi_judul);
                })->map(function ($item) {
                    return [
                        'judul' => $item->publikasi_judul,
                        'jurnal' => $item->publikasi_jurnal,
                        'tahun' => $item->publikasi_tahun,
                    ];
                })->values(),
                'penelitian' => $dosenGroup->filter(function ($item) {
                    return !is_null($item->penelitian_judul);
                })->map(function ($item) {
                    return [
                        'judul' => $item->penelitian_judul,
                        'tahun' => $item->penelitian_tahun,
                    ];
                })->values(),
            ];
        });

        // Konversi hasil ke bentuk JSON array objek
        return response()->json($dosenData->values());
    }

    public function perwalian(){
        $results = DB::table('dosen as d')
            ->join('perwalian as pw', 'pw.id_dosen', '=', 'd.id')
            ->join('mahasiswa as m', 'm.id', '=', 'pw.id_mahasiswa')
            ->select(
                'd.id as dosen_id', 
                'd.nama as dosen_nama', 
                'd.nip',
                'm.id as mahasiswa_id', 
                'm.nama as mahasiswa_nama', 
                'm.nim'
            )
            ->orderBy('d.id')
            ->orderBy('m.id')
            ->get();

        // Format hasil ke dalam bentuk JSON yang diinginkan
        $dosenData = $results->groupBy('dosen_id')->map(function ($dosenGroup) {
            return [
                'dosen_id' => $dosenGroup->first()->dosen_id,
                'nama' => $dosenGroup->first()->dosen_nama,
                'nip' => $dosenGroup->first()->nip,
                'perwalian' => $dosenGroup->map(function ($item) {
                    return [
                        'mahasiswa_id' => $item->mahasiswa_id,
                        'nama' => $item->mahasiswa_nama,
                        'nim' => $item->nim,
                    ];
                })->values(),
            ];
        });

        // Konversi hasil ke bentuk JSON array objek
        return response()->json($dosenData->values());

    }

    public function bimbingan(){
        $results = DB::table('dosen as d')
            ->leftJoin('bimbingan_mahasiswa as bm', 'bm.id_dosen', '=', 'd.id')
            ->leftJoin('mahasiswa as m', 'bm.id_mahasiswa', '=', 'm.id')
            ->leftJoin('log_bimbingan_mahasiswa as lbm', 'lbm.id_bimbingan', '=', 'bm.id')
            ->leftJoin('bimbingan_kp as bkp', function ($join) {
                $join->on('bkp.id_dosen', '=', 'd.id')
                    ->on('bkp.id_mahasiswa', '=', 'm.id');
            })
            ->leftJoin('log_bimbingan_kp as lbkp', 'lbkp.id_bimbingan_kp', '=', 'bkp.id')
            ->select(
                'd.id as dosen_id', 'd.nama as dosen_nama', 'd.nip',
                'bm.id as bimbingan_id', 'bm.topik as bimbingan_topik', 'm.nama as mahasiswa_nama',
                'lbm.catatan as log_bimbingan_catatan', 'lbm.tanggal as log_bimbingan_tanggal',
                'bkp.id as bimbingan_kp_id', 'bkp.topik as bimbingan_kp_topik',
                'lbkp.catatan as log_bimbingan_kp_catatan', 'lbkp.tanggal as log_bimbingan_kp_tanggal'
            )
            ->orderBy('d.id')
            ->orderBy('bm.id')
            ->orderBy('bkp.id')
            ->get();

            $dosenData = $results->groupBy('dosen_id')->map(function ($dosenGroup) {
                // Bimbingan Skripsi
                $bimbinganSkripsi = $dosenGroup->groupBy('bimbingan_id')->map(function ($bimbinganGroup) {
                    // Mapping mahasiswa dan log bimbingan
                    $mahasiswa = [
                        'nama' => $bimbinganGroup->first()->mahasiswa_nama,
                        'log' => $bimbinganGroup->map(function ($logItem) {
                            return [
                                'catatan' => $logItem->log_bimbingan_catatan,
                                'tanggal' => $logItem->log_bimbingan_tanggal,
                            ];
                        })->filter()->values()->toArray(),
                    ];
            
                    return [
                        'id' => $bimbinganGroup->first()->bimbingan_id,
                        'topik' => $bimbinganGroup->first()->bimbingan_topik,
                        'mahasiswa' => $mahasiswa,
                    ];
                })->values()->toArray();
            
                // Bimbingan KP
                $bimbinganKP = $dosenGroup->groupBy('bimbingan_kp_id')->map(function ($bimbinganKPGroup) {
                    // Mapping log bimbingan KP
                    $mahasiswa = [
                        'nama' => $bimbinganKPGroup->first()->mahasiswa_nama,
                        'log' => $bimbinganKPGroup->map(function ($logKPItem) {
                            return [
                                'catatan' => $logKPItem->log_bimbingan_kp_catatan,
                                'tanggal' => $logKPItem->log_bimbingan_kp_tanggal,
                            ];
                        })->filter()->values()->toArray(),
                    ];
            
                    return [
                        'id' => $bimbinganKPGroup->first()->bimbingan_kp_id,
                        'topik' => $bimbinganKPGroup->first()->bimbingan_kp_topik,
                        'mahasiswa' => $mahasiswa,
                    ];
                })->values()->toArray();
            
                return [
                    'id' => $dosenGroup->first()->dosen_id,
                    'nama' => $dosenGroup->first()->dosen_nama,
                    'nip' => $dosenGroup->first()->nip,
                    'bimbingan_skripsi' => $bimbinganSkripsi,
                    'bimbingan_kp' => $bimbinganKP,
                ];
            });
            
            // Menghasilkan format JSON
            return $dosenData->values()->toArray();            
    }

    public function aktivitasPerkuliahan(){
        $results = DB::table('dosen as d')
            ->join('kelompok as k', 'k.id_dosen', '=', 'd.id')
            ->join('mata_kuliah as mk', 'mk.id', '=', 'k.id_matkul')
            ->join('jadwal as j', 'j.id_kelompok', '=', 'k.id')
            ->join('mahasiswa as m', 'm.id', '=', 'j.id_mahasiswa') // Join mahasiswa sebelum nilai
            ->join('nilai as n', function ($join) {
                $join->on('n.id_kelompok', '=', 'k.id')
                    ->on('n.id_mahasiswa', '=', 'm.id');
            })
            ->join('absensi as a', function ($join) {
                $join->on('a.id_jadwal', '=', 'j.id')
                    ->on('a.id_mahasiswa', '=', 'm.id');
            })
            ->select(
                'd.id as dosen_id', 'd.nama as dosen_nama', 'd.nip',
                'mk.id as matkul_id', 'mk.nama_matkul', 'mk.sks',
                'k.id as kelompok_id', 'k.nama_kelompok',
                'n.id as nilai_id', 'n.nilai_tugas_akhir', 'n.nilai_uts', 'n.nilai_uas',
                'm.id as mahasiswa_id', 'm.nama as mahasiswa_nama', 'm.nim',
                'j.id as jadwal_id', 'j.hari', 'j.jam_mulai', 'j.jam_selesai',
                'a.tanggal as absensi_tanggal', 'a.status as absensi_status'
            )
            ->orderBy('d.id')
            ->orderBy('mk.id')
            ->orderBy('k.id')
            ->orderBy('j.id')
            ->orderBy('m.id')
            ->orderBy('a.tanggal')
            ->get();


        // Format data ke dalam bentuk JSON yang sesuai
        $dosenData = $results->groupBy('dosen_id')->map(function ($dosenGroup) {
            $mataKuliahData = $dosenGroup->groupBy('matkul_id')->map(function ($matkulGroup) {
                $kelompokData = $matkulGroup->groupBy('kelompok_id')->map(function ($kelompokGroup) {
                    $nilaiData = $kelompokGroup->groupBy('nilai_id')->map(function ($nilaiGroup) {
                        return [
                            'tugas_akhir' => $nilaiGroup->first()->nilai_tugas_akhir,
                            'uts' => $nilaiGroup->first()->nilai_uts,
                            'uas' => $nilaiGroup->first()->nilai_uas,
                        ];
                    });

                    $jadwalData = $kelompokGroup->groupBy('jadwal_id')->map(function ($jadwalGroup) {
                        $mahasiswaData = $jadwalGroup->groupBy('mahasiswa_id')->map(function ($mahasiswaGroup) {
                            $absensiData = $mahasiswaGroup->map(function ($item) {
                                return [
                                    'tanggal' => $item->absensi_tanggal,
                                    'status' => $item->absensi_status,
                                ];
                            });

                            return [
                                'id' => $mahasiswaGroup->first()->mahasiswa_id,
                                'nama' => $mahasiswaGroup->first()->mahasiswa_nama,
                                'nim' => $mahasiswaGroup->first()->nim,
                                'absensi' => $absensiData->toArray(),
                            ];
                        });

                        return [
                            'id' => $jadwalGroup->first()->jadwal_id,
                            'hari' => $jadwalGroup->first()->hari,
                            'jam_mulai' => $jadwalGroup->first()->jam_mulai,
                            'jam_selesai' => $jadwalGroup->first()->jam_selesai,
                            'mahasiswa' => $mahasiswaData->values()->toArray(),
                        ];
                    });

                    return [
                        'id' => $kelompokGroup->first()->kelompok_id,
                        'nama_kelompok' => $kelompokGroup->first()->nama_kelompok,
                        'nilai' => $nilaiData->values()->toArray(),
                        'jadwal' => $jadwalData->values()->toArray(),
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

        // Mengembalikan hasil dalam bentuk JSON
        return $dosenData->values()->toArray();

    }
}
