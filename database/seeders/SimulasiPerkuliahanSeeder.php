<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Kelompok;
use App\Models\Jadwal;
use App\Models\JadwalMahasiswa;
use App\Models\Nilai;
use App\Models\Absensi;
use App\Models\Publikasi;
use App\Models\Penelitian;
use App\Models\Pengabdian;

use App\Models\BimbinganMahasiswa;
use App\Models\LogBimbinganMahasiswa;
use App\Models\BimbinganKP;
use App\Models\LogBimbinganKP;

use App\Models\Pertemuan;
use App\Models\BahanAjar;
use App\Models\Penugasan;
use App\Models\IsianPenugasanMahasiswa;

class SimulasiPerkuliahanSeeder extends Seeder
{
    public function run()
    {
        // Langkah 1: Buat Faker Dosen, Mahasiswa, dan Mata Kuliah
        $dosen = Dosen::factory()->count(10)->create();         // 10 dosen
        $mahasiswa = Mahasiswa::factory()->count(150)->create(); // 150 mahasiswa
        $mataKuliah = MataKuliah::factory()->count(20)->create(); // 20 mata kuliah

        // Langkah 2: Batasi Dosen Maksimal 16 SKS dan Tambahkan Perwalian
        $dosen->each(function ($dosen) use ($mataKuliah, $mahasiswa) {
            // Tambahkan data terkait publikasi, penelitian, dan pengabdian
            Publikasi::factory()->count(3)->create(['id_dosen' => $dosen->id]);
            Penelitian::factory()->count(2)->create(['id_dosen' => $dosen->id]);
            Pengabdian::factory()->create(['id_dosen' => $dosen->id]);

            // Langkah Perwalian: Assign 15 mahasiswa ke dosen sebagai wali
            $mahasiswaWali = $mahasiswa->random(15); // Pilih 15 mahasiswa secara acak
            $mahasiswaWali->each(function ($mhs) use ($dosen) {
                // Simpan relasi perwalian di tabel 'perwalian'
                DB::table('perwalian')->insert([
                    'id_dosen' => $dosen->id,
                    'id_mahasiswa' => $mhs->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

            $totalSKS = 0;
            $kelompokList = [];

            while ($totalSKS < 16) {
                $matkul = $mataKuliah->random(); // Ambil mata kuliah acak
                $sks = $matkul->sks;

                // Pastikan total SKS tidak melebihi 16
                if (($totalSKS + $sks) > 16) {
                    break;
                }

                // Buat kelompok untuk dosen dan mata kuliah
                $kelompok = Kelompok::factory()->create([
                    'id_dosen' => $dosen->id,
                    'id_matkul' => $matkul->id,
                ]);

                $kelompokList[] = $kelompok;
                $totalSKS += $sks;

                // Langkah 3: Buat Jadwal
                $jumlahJadwal = $sks == 4 ? 2 : 1;

                $jadwalList = Jadwal::factory()->count($jumlahJadwal)->create([
                    'id_matkul' => $kelompok->id_matkul,
                    'id_dosen' => $kelompok->id_dosen,
                    'id_kelompok' => $kelompok->id,
                ]);

                // Langkah 4: Buat Pertemuan, Bahan Ajar, dan Penugasan
                foreach ($jadwalList as $jadwal) {
                    $pertemuanId = Pertemuan::insertGetId([
                        'id_kelompok' => $kelompok->id,
                        'minggu' => 'Minggu ke-' . rand(1, 14),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Buat Bahan Ajar untuk Pertemuan
                    BahanAjar::insert([
                        'id_kelompok' => $kelompok->id,
                        'id_pertemuan' => $pertemuanId,
                        'nama_bahan' => 'Bahan Ajar ' . rand(1, 10),
                        'tipe_bahan' => ['Dokumen', 'Video', 'Lainnya'][rand(0, 2)],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Buat Penugasan untuk Pertemuan
                    $penugasanId = Penugasan::insertGetId([
                        'id_kelompok' => $kelompok->id,
                        'id_pertemuan' => $pertemuanId,
                        'nama_tugas' => 'Penugasan ' . rand(1, 10),
                        'deskripsi' => 'Deskripsi penugasan ' . rand(1, 10),
                        'tenggat' => Carbon::now()->addWeeks(rand(1, 3)),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Langkah 5: Buat Isian Penugasan Mahasiswa
                    $mahasiswaKelompok = $mahasiswa->filter(function ($mhs) {
                        // Mahasiswa hanya bisa mengikuti maksimal 20 SKS
                        if (!isset($mhs->total_sks)) {
                            $mhs->total_sks = 0;
                        }
                        return $mhs->total_sks < 20;
                    })->random(rand(3, 10)); // Maksimal 10 mahasiswa per kelompok

                    foreach ($mahasiswaKelompok as $mhs) {
                        $sks = $kelompok->mataKuliah->sks;
                        if ($mhs->total_sks + $sks <= 20) 
                        {
                            // Buat nilai mahasiswa di kelompok tersebut
                            Nilai::factory()->create([
                                'id_mahasiswa' => $mhs->id,
                                'id_kelompok' => $kelompok->id,
                            ]);

                            IsianPenugasanMahasiswa::insert([
                                'id_pertemuan' => $pertemuanId,
                                'id_penugasan' => $penugasanId,
                                'id_mahasiswa' => $mhs->id,
                                'jawaban' => 'Jawaban mahasiswa ' . $mhs->nama,
                                'nilai' => rand(60, 100),
                                'tanggal_pengumpulan' => Carbon::now()->subDays(rand(0, 10)),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

                            // Tambahkan SKS ke total mahasiswa
                            $mhs->total_sks += $sks;

                            // Simpan relasi mahasiswa dan kelompok di tabel jadwal_mahasiswa
                            JadwalMahasiswa::insert([
                                'id_mahasiswa' => $mhs->id,
                                'id_kelompok' => $kelompok->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        });

        // Langkah 6: Buat Absensi
        $mahasiswa->each(function ($mahasiswa) {
            $jadwal = Jadwal::inRandomOrder()->first(); 
            $startDate = Carbon::create(2021, 1, 1);

            for ($i = 0; $i < rand(5, 10); $i++) {
                Absensi::factory()->create([
                    'id_mahasiswa' => $mahasiswa->id,
                    'id_jadwal' => $jadwal->id,
                    'tanggal' => $startDate->copy()->addWeeks($i),
                ]);
            }
        });

        // Langkah 6: Tambahkan Bimbingan dan Log Bimbingan
        $dosen->each(function ($dosen) use ($mahasiswa) {
            $bimbinganMahasiswa = $mahasiswa->random(5); // Pilih 5 mahasiswa untuk bimbingan

            $bimbinganMahasiswa->each(function ($mhs) use ($dosen) {
                // Buat bimbingan
                $bimbinganId = BimbinganMahasiswa::insertGetId([
                    'id_dosen' => $dosen->id,
                    'id_mahasiswa' => $mhs->id,
                    'topik' => 'Judul Skripsi Mahasiswa',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Buat log bimbingan
                for ($i = 0; $i < rand(2, 5); $i++) {
                    LogBimbinganMahasiswa::insert([
                        'id_bimbingan' => $bimbinganId,
                        'catatan' => 'Pertemuan ke-' . ($i + 1),
                        'tanggal' => Carbon::now()->subWeeks($i),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });
        });

        // Langkah 7: Tambahkan Bimbingan KP dan Log Bimbingan KP
        $dosen->each(function ($dosen) use ($mahasiswa) {
            $bimbinganKPMahasiswa = $mahasiswa->random(5); // Pilih 5 mahasiswa untuk bimbingan KP

            $bimbinganKPMahasiswa->each(function ($mhs) use ($dosen) {
                // Buat bimbingan KP
                $bimbinganKPId = BimbinganKP::insertGetId([
                    'id_dosen' => $dosen->id,
                    'id_mahasiswa' => $mhs->id,
                    'topik' => 'Kerja Praktik',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Buat log bimbingan KP
                for ($i = 0; $i < rand(2, 5); $i++) {
                    LogBimbinganKP::insert([
                        'id_bimbingan_kp' => $bimbinganKPId,
                        'catatan' => 'Pertemuan KP ke-' . ($i + 1),
                        'tanggal' => Carbon::now()->subWeeks($i),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });
        });
    }
}
