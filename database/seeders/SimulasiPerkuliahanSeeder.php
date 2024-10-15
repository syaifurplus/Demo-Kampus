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
        $dosen = Dosen::factory()->count(10)->create();         // 10 dosen
        $mahasiswa = Mahasiswa::factory()->count(50)->create(); // 50 mahasiswa
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
                    $mahasiswaGroup = $mahasiswa->random(rand(3, 7)); // Pilih mahasiswa acak

                    $mahasiswaGroup->each(function ($mahasiswa) use ($kelompok) {
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
    }
}
