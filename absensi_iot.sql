-- ============================================
-- Database: absensi_iot
-- ============================================
CREATE DATABASE IF NOT EXISTS absensi_iot;
USE absensi_iot;
-- Tabel kelas
CREATE TABLE kelas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kelas VARCHAR(100) NOT NULL,
    mata_kuliah VARCHAR(100) NOT NULL,
    ruangan VARCHAR(100) NOT NULL,
    jam_masuk TIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;
-- Tabel users (data murid)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    no_hp VARCHAR(20) DEFAULT NULL,
    uid_rfid VARCHAR(20) NOT NULL UNIQUE,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    kelas_id INT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;
-- Tabel relasi user dan mata kuliah
CREATE TABLE user_mata_kuliah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    mata_kuliah VARCHAR(100) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_mata_kuliah (user_id, mata_kuliah),
    KEY idx_user_mata_kuliah_mata_kuliah (mata_kuliah),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE = InnoDB;
-- Tabel absensi
CREATE TABLE absensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    kelas_id INT DEFAULT NULL,
    tanggal DATE NOT NULL,
    waktu TIME NOT NULL,
    status ENUM('hadir', 'telat') NOT NULL DEFAULT 'hadir',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_absen (user_id, tanggal, kelas_id)
) ENGINE = InnoDB;
-- Tabel invalid_cards (kartu belum terdaftar)
CREATE TABLE invalid_cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uid_rfid VARCHAR(20) NOT NULL,
    tanggal DATE NOT NULL,
    waktu TIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;
-- Tabel settings
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value VARCHAR(100) NOT NULL
) ENGINE = InnoDB;
-- Tabel admin (login admin)
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

-- Default admin: admin / admin123
INSERT INTO admins (username, password)
VALUES ('admin', '$2y$12$1a4iukUnzFqVWk/1K0aaMuvkxuZuWSHg/WZ04xYP7FCBLXyJEZlQy');

-- Default setting: jam masuk dan timezone aplikasi
INSERT INTO settings (setting_key, setting_value)
VALUES ('jam_masuk', '07:00'),
    ('timezone', 'Asia/Makassar'),
    ('active_kelas_id', '');
-- Sample data kelas (opsional, untuk testing)
INSERT INTO kelas (kelas, mata_kuliah, ruangan, jam_masuk)
VALUES ('TI-1A', 'Pemrograman Dasar', 'Lab 1', '07:00:00'),
    ('TI-1B', 'Basis Data', 'Lab 2', '08:00:00'),
    ('SI-2A', 'Jaringan Komputer', 'Ruang 201', '09:00:00');
-- Sample data (opsional, untuk testing)
INSERT INTO users (nama, no_hp, uid_rfid, jenis_kelamin, kelas_id)
VALUES ('Ahmad Rizki', '081234567890', 'A1B2C3D4', 'L', 1),
    ('Siti Aminah', '081298765432', 'E5F6G7H8', 'P', 2),
    ('Budi Santoso', '081355512345', 'I9J0K1L2', 'L', 3);
INSERT INTO user_mata_kuliah (user_id, mata_kuliah)
VALUES (1, 'Pemrograman Dasar'),
    (2, 'Basis Data'),
    (3, 'Jaringan Komputer');
