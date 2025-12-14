# Database Structure - Manajemen Masjid Al-Nassr

## Overview
Database ini berisi 30+ tabel yang saling berelasi untuk sistem manajemen masjid yang terintegrasi dengan modul dari kelompok lain.

## Tabel Utama

### 1. Authentication & User Management
- **users** - Data pengguna (jemaah, pengurus, admin)
- **jabatan** - Struktur jabatan pengurus
- **riwayat_jabatan** - Riwayat jabatan pengurus

### 2. Event Management
- **events** - Data kegiatan masjid
- **sesi_event** - Sesi/jadwal untuk setiap event
- **peserta_event** - Peserta yang mendaftar ke event
- **pengajuan_event** - Pengajuan event baru oleh jemaah

### 3. Tugas & Piket Pengurus
- **tugas_tanggung_jawab** - Daftar tugas per jabatan
- **penugasan_tugas** - Penugasan tugas ke pengurus
- **jadwal_piket** - Jadwal piket pengurus

### 4. Informasi & Komunikasi
- **artikel** - Artikel keislaman
- **berita** - Berita masjid
- **pengumuman** - Pengumuman untuk jemaah
- **notifikasi** - Notifikasi ke pengguna
- **jadwal_sholat** - Jadwal waktu sholat

### 5. Manajemen Keuangan (DKM)
- **dana_dkm** - Dana masuk DKM
- **dana_operasional** - Pengeluaran operasional

### 6. Zakat, Infaq, Shadaqah (ZIS)
- **muzakki** - Data pemberi zakat
- **mustahik** - Data penerima zakat
- **zis_masuk** - Transaksi ZIS masuk
- **penyaluran** - Penyaluran ZIS ke mustahik
- **petugas_zis** - Data petugas pengelola ZIS

### 7. Kurban (Qurban Management)
- **jadwal_penyembelih** - Jadwal penyembelihan
- **hewan_kurban** - Data hewan kurban
- **penerima_kurban** - Data penerima kurban
- **distribusi_daging** - Distribusi daging kurban

### 8. Inventaris & Perawatan
- **inventaris** - Aset/barang masjid
- **perawatan** - Jadwal perawatan aset
- **laporan_perawatan** - Laporan hasil perawatan

## Relasi Utama

### Event Flow
```
events (1) -> (N) sesi_event (1) -> (N) peserta_event (N) -> (1) users
```

### Approval Flow
```
users (jemaah) -> pengajuan_event -> approved by users (pengurus/admin)
```

### ZIS Flow
```
muzakki -> zis_masuk -> penyaluran -> mustahik
```

### Kurban Flow
```
dana_operasional -> jadwal_penyembelih -> hewan_kurban -> distribusi_daging -> penerima_kurban
```

## Migration Files
1. `0001_01_01_000000_create_users_table.php` - Tabel users
2. `0001_01_01_000001_create_cache_table.php` - Cache Laravel
3. `0001_01_01_000002_create_jobs_table.php` - Queue jobs
4. `2025_12_13_223339_create_events_table.php` - Tabel events
5. `2025_12_14_000002_create_all_tables.php` - Semua tabel lainnya (27 tabel)

## Models Created
- User (sudah ada dengan SoftDeletes)
- Event (dengan relasi ke SesiEvent)
- PengajuanEvent
- SesiEvent
- PesertaEvent
- JadwalSholat
- Inventaris

## Notes
- Semua tabel menggunakan timestamp `created_at` dan `updated_at`
- Tabel users menggunakan SoftDeletes (`deleted_at`)
- Primary key mengikuti struktur SQL file:
  - events: `event_id`
  - sesi_event: `sesi_event_id`
  - peserta_event: `peserta_event_id`
  - dll.
- Foreign keys sudah diatur sesuai dengan relasi di SQL file
- Enum values mengikuti struktur SQL file (contoh: role = 'jemaah', 'pengurus', 'admin')

## Database Seeder
Default users sudah di-seed:
- admin/admin123 (role: admin)
- dkm/dkm123 (role: pengurus)
- panitia/panitia123 (role: pengurus)
- jamaah/jamaah123 (role: jemaah)
- budi/password (role: jemaah)
