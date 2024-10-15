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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
            $totalSKS = 0;
            $kelompokList = [];

            // Membuat array untuk menyimpan id_matkul yang sudah diambil oleh dosen
            $mataKuliahDosen = [];

            while ($totalSKS < 16) {
                // Ambil mata kuliah acak yang belum diambil oleh dosen
                $matkul = $mataKuliah->whereNotIn('id', $mataKuliahDosen)->random();
                $sks = $matkul->sks;

                // Pastikan total SKS tidak melebihi 16
                if (($totalSKS + $sks) > 16) {
                    break;
                }

                // Simpan id mata kuliah yang sudah diambil oleh dosen
                $mataKuliahDosen[] = $matkul->id;

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

                Jadwal::factory()->count($jumlahJadwal)->create([
                    'id_matkul' => $kelompok->id_matkul,
                    'id_dosen' => $kelompok->id_dosen,
                    'id_kelompok' => $kelompok->id,
                ]);

                // Langkah 4: Buat Nilai dan Relasi untuk Setiap Mahasiswa di Kelompok yang Dibuat
                $mataKuliahMahasiswa = []; // Menyimpan mata kuliah yang sudah diambil oleh mahasiswa

                $mahasiswaGroup = $mahasiswa->random(rand(3, 7)); // Pilih mahasiswa acak

                $mahasiswaGroup->each(function ($mahasiswa) use ($kelompok, &$mataKuliahMahasiswa) {
                    // Cek apakah mahasiswa sudah mengambil mata kuliah ini
                    if (!in_array($kelompok->id_matkul, $mataKuliahMahasiswa)) {
                        // Buat nilai mahasiswa di kelompok tersebut
                        Nilai::factory()->create([
                            'id_mahasiswa' => $mahasiswa->id,
                            'id_kelompok' => $kelompok->id,
                        ]);

                        // Simpan mata kuliah yang sudah diambil mahasiswa
                        $mataKuliahMahasiswa[] = $kelompok->id_matkul;

                        // Simpan relasi mahasiswa dan kelompok di tabel jadwal_mahasiswa
                        DB::table('jadwal_mahasiswa')->insert([
                            'id_mahasiswa' => $mahasiswa->id,
                            'id_kelompok' => $kelompok->id, // Simpan id_kelompok di tabel jadwal_mahasiswa
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                });
            }
        });

        // Langkah 5: Buat Absensi untuk Setiap Mahasiswa dan Jadwal yang Ada
        $mahasiswa->each(function ($mahasiswa) {
            // Ambil jadwal acak
            $jadwal = Jadwal::inRandomOrder()->first(); 

            // Tentukan berapa kali mahasiswa ini akan melakukan absensi (antara 5 hingga 10 kali)
            $jumlahAbsensi = rand(5, 10);

            // Buat tanggal absensi setiap minggu dari hari ini
            $startDate = Carbon::now()->startOfWeek(); // Mulai dari awal minggu ini

            for ($i = 0; $i < $jumlahAbsensi; $i++) {
                // Tambahkan absensi setiap minggu
                Absensi::factory()->create([
                    'id_mahasiswa' => $mahasiswa->id,
                    'id_jadwal' => $jadwal->id,
                    'tanggal' => $startDate->copy()->addWeeks($i), // Tanggal absensi setiap minggu
                ]);
            }
        });
    }
}
