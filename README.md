# Aplikasi Manajemen Event Masjid

Sistem manajemen event masjid dengan role-based access control untuk mengelola kegiatan dan acara masjid.

## Cara Menjalankan Aplikasi

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Konfigurasi Database
Edit file `.env` sesuaikan dengan database Anda:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username
DB_PASSWORD=password
```

### 4. Migrasi & Seeder
```bash
php artisan migrate:fresh --seed
```

### 5. Build Assets
```bash
npm run build
```
atau untuk development:
```bash
npm run dev
```

### 6. Jalankan Server
```bash
php artisan serve
```

Aplikasi akan berjalan di: `http://127.0.0.1:8000`

---

## Akun Login

| Role | Username | Password | Deskripsi |
|------|----------|----------|-----------|
| **Admin** | `admin` | `admin123` | Full access ke seluruh sistem |
| **DKM** | `dkm` | `dkm123` | Approval event & manajemen masjid |
| **Panitia 1** | `panitia` | `panitia123` | Membuat event baru |
| **Panitia 2** | `panitia2` | `panitia123` | Membuat event baru |
| **Jemaah 1** | `jamaah` | `jamaah123` | Peserta event |
| **Jemaah 2** | `budi` | `password` | Peserta event |
| **Jemaah 3** | `siti` | `password` | Peserta event |

---

## Sistem Role

- **Admin**: Mengelola seluruh sistem, user, dan event
- **DKM**: Menyetujui/menolak event yang diajukan Panitia
- **Panitia**: Membuat dan mengelola event (status draft, menunggu approval DKM)
- **Jemaah**: Melihat dan mendaftar event yang sudah dipublish
