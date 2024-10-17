<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Kelompok;
use App\Models\Jadwal;
use App\Models\Nilai;
use App\Models\Absensi;
use App\Models\Publikasi;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SimulasiPerkuliahanSeeder extends Seeder
{
    public function run()
    {
        // Langkah 1: Buat Faker Dosen, Mahasiswa, dan Mata Kuliah
        $dosen = Dosen::factory()->count(1)->create();         // 10 dosen
        $mahasiswa = Mahasiswa::factory()->count(150)->create(); // 50 mahasiswa
        $mataKuliah = MataKuliah::factory()->count(20)->create(); // 20 mata kuliah

        // Langkah 2: Batasi Dosen Maksimal 16 SKS
        $dosen->each(function ($dosen) use ($mataKuliah, $mahasiswa) {
            // Buat 3 publikasi untuk setiap dosen
            Publikasi::factory()->count(3)->create([
                'id_dosen' => $dosen->id,
            ]);

            // Buat 2 penelitian untuk setiap dosen
            Penelitian::factory()->count(2)->create([
                'id_dosen' => $dosen->id,
            ]);

            // Buat 1 pengabdian untuk setiap dosen
            Pengabdian::factory()->create([
                'id_dosen' => $dosen->id,
            ]);

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

                // Langkah 3: Buat Jadwal sesuai dengan jumlah SKS
                // Jika 2 atau 3 SKS, buat hanya 1 jadwal
                // Jika 4 SKS, buat 2 jadwal
                $jumlahJadwal = $sks == 4 ? 2 : 1;

                $jadwalList = Jadwal::factory()->count($jumlahJadwal)->create([
                    'id_matkul' => $kelompok->id_matkul,
                    'id_dosen' => $kelompok->id_dosen,
                    'id_kelompok' => $kelompok->id,
                ]);

                // Langkah 4: Buat Nilai untuk Setiap Mahasiswa di Kelompok yang Dibuat
                $kelompokCollection = collect($kelompokList);

                $kelompokCollection->each(function ($kelompok) use ($mahasiswa) {
                    $mahasiswaGroup = $mahasiswa->filter(function ($mahasiswa) {
                        // Tambahkan properti `total_sks` jika belum ada
                        if (!isset($mahasiswa->total_sks)) {
                            $mahasiswa->total_sks = 0;
                        }
                        // Hanya pilih mahasiswa dengan total SKS kurang dari 20
                        return $mahasiswa->total_sks < 20;
                    })->random(rand(3, 5)); // Pilih mahasiswa acak, antara 3 sampai 5
                
                    $mahasiswaGroup->each(function ($mahasiswa) use ($kelompok) {
                        $sks = $kelompok->mataKuliah->sks; // Ambil SKS dari mata kuliah di kelompok ini
                
                        // Pastikan penambahan SKS tidak melebihi 20
                        if ($mahasiswa->total_sks + $sks <= 20) {
                            // Buat nilai mahasiswa di kelompok tersebut
                            Nilai::factory()->create([
                                'id_mahasiswa' => $mahasiswa->id,
                                'id_kelompok' => $kelompok->id,
                            ]);
                
                            // Simpan relasi mahasiswa dan kelompok di tabel jadwal_mahasiswa
                            DB::table('jadwal_mahasiswa')->insert([
                                'id_mahasiswa' => $mahasiswa->id,
                                'id_kelompok' => $kelompok->id, // Simpan id_kelompok di tabel jadwal_mahasiswa
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                
                            // Tambahkan SKS ke total mahasiswa
                            $mahasiswa->total_sks += $sks;
                        }
                    });
                });                
            }
        });

        // Langkah 5: Buat Absensi untuk Setiap Mahasiswa dan Jadwal yang Ada
        $mahasiswa->each(function ($mahasiswa) {
            // Ambil jadwal acak
            $jadwal = Jadwal::inRandomOrder()->first(); 
        
            // Mulai dari tanggal tertentu, misalnya 1 Januari 2021
            $startDate = Carbon::create(2021, 1, 1);
        
            // Loop untuk membuat absensi 5 hingga 10 kali untuk tiap mahasiswa
            for ($i = 0; $i < rand(5, 10); $i++) {
                Absensi::factory()->create([
                    'id_mahasiswa' => $mahasiswa->id,
                    'id_jadwal' => $jadwal->id,
                    'tanggal' => $startDate->copy()->addWeeks($i), // Tambahkan minggu untuk setiap loop
                ]);
            }
        });

        // Langkah 6: Tambahkan Bimbingan dan Log Bimbingan
        $dosen->each(function ($dosen) use ($mahasiswa) {
            $bimbinganMahasiswa = $mahasiswa->random(5); // Pilih 5 mahasiswa untuk bimbingan

            $bimbinganMahasiswa->each(function ($mhs) use ($dosen) {
                // Buat bimbingan
                $bimbinganId = DB::table('bimbingan')->insertGetId([
                    'id_dosen' => $dosen->id,
                    'id_mahasiswa' => $mhs->id,
                    'topik' => 'Bimbingan Akademik',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Buat log bimbingan
                for ($i = 0; $i < rand(2, 5); $i++) {
                    DB::table('log_bimbingan')->insert([
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
                $bimbinganKPId = DB::table('bimbingan_kp')->insertGetId([
                    'id_dosen' => $dosen->id,
                    'id_mahasiswa' => $mhs->id,
                    'judul' => 'Kerja Praktik',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Buat log bimbingan KP
                for ($i = 0; $i < rand(2, 5); $i++) {
                    DB::table('log_bimbingan_kp')->insert([
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
