-- ======================= Master =============================================

-- Tabel dosen
CREATE TABLE dosen (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255),
    nip VARCHAR(100) UNIQUE,
    email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel mahasiswa
CREATE TABLE mahasiswa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255),
    nim VARCHAR(100) UNIQUE,
    email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel mata kuliah
CREATE TABLE mata_kuliah (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kode_matkul VARCHAR(50),
    nama_matkul VARCHAR(255),
    sks INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);



-- Tabel kelompok
CREATE TABLE kelompok (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_matkul INT,
    id_dosen INT,
    nama_kelompok VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_dosen) REFERENCES dosen(id),
    FOREIGN KEY (id_matkul) REFERENCES mata_kuliah(id)
);


-- Tabel jadwal
CREATE TABLE jadwal (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_matkul INT,
    id_dosen INT,
    id_kelompok INT,
    hari VARCHAR(20),
    jam_mulai TIME,
    jam_selesai TIME,
    ruang VARCHAR(100),
    FOREIGN KEY (id_matkul) REFERENCES mata_kuliah(id),
    FOREIGN KEY (id_dosen) REFERENCES dosen(id),
    FOREIGN KEY (id_kelompok) REFERENCES kelompok(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel nilai (UTS, UAS, Tugas Akhir)
CREATE TABLE nilai (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_mahasiswa INT,
    id_kelompok INT,
    nilai_uts DECIMAL(5,2),
    nilai_uas DECIMAL(5,2),
    nilai_tugas_akhir DECIMAL(5,2),
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id),
    FOREIGN KEY (id_kelompok) REFERENCES kelompok(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel absensi
CREATE TABLE absensi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_mahasiswa INT,
    id_jadwal INT,
    tanggal DATE,
    status ENUM('Hadir', 'Tidak Hadir'),
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id),
    FOREIGN KEY (id_jadwal) REFERENCES jadwal(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel perwalian
CREATE TABLE perwalian (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_dosen INT,
    id_mahasiswa INT,
    status_validasi ENUM('Validasi', 'Belum Validasi'),
    FOREIGN KEY (id_dosen) REFERENCES dosen(id),
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel bimbingan mahasiswa
CREATE TABLE bimbingan_mahasiswa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_mahasiswa INT,
    id_dosen INT,
    topik VARCHAR(255),
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id),
    FOREIGN KEY (id_dosen) REFERENCES dosen(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel log bimbingan mahasiswa
CREATE TABLE log_bimbingan_mahasiswa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_bimbingan INT,
    tanggal DATE,
    catatan TEXT,
    FOREIGN KEY (id_bimbingan) REFERENCES bimbingan_mahasiswa(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel bimbingan KP
CREATE TABLE bimbingan_kp (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_mahasiswa INT,
    id_dosen INT,
    topik VARCHAR(255),
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id),
    FOREIGN KEY (id_dosen) REFERENCES dosen(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel log bimbingan KP
CREATE TABLE log_bimbingan_kp (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_bimbingan INT,
    tanggal DATE,
    catatan TEXT,
    FOREIGN KEY (id_bimbingan) REFERENCES bimbingan_kp(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel pengabdian
CREATE TABLE pengabdian (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_dosen INT,
    judul VARCHAR(255),
    tahun INT,
    FOREIGN KEY (id_dosen) REFERENCES dosen(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel publikasi
CREATE TABLE publikasi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_dosen INT,
    judul VARCHAR(255),
    jurnal VARCHAR(255),
    tahun INT,
    FOREIGN KEY (id_dosen) REFERENCES dosen(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel penelitian
CREATE TABLE penelitian (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_dosen INT,
    judul VARCHAR(255),
    tahun INT,
    FOREIGN KEY (id_dosen) REFERENCES dosen(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel bahan ajar
CREATE TABLE bahan_ajar (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_kelompok INT,
    nama_bahan VARCHAR(255),
    tipe_bahan ENUM('Dokumen', 'Video', 'Lainnya'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel penugasan
CREATE TABLE penugasan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_kelompok INT,
    nama_tugas VARCHAR(255),
    deskripsi TEXT,
    tenggat DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Foreign key untuk menghubungkan dengan tabel kelompok
    -- FOREIGN KEY (id_kelompok) REFERENCES kelompok(id)
);

-- Tabel isian penugasan mahasiswa
CREATE TABLE isian_penugasan_mahasiswa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_penugasan INT,
    id_mahasiswa INT,
    jawaban TEXT,
    nilai DECIMAL(5,2),
    tanggal_pengumpulan DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Foreign key untuk menghubungkan dengan tabel penugasan dan mahasiswa
    FOREIGN KEY (id_penugasan) REFERENCES penugasan(id),
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id)
);