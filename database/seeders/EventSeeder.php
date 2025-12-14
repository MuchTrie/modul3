<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get panitia user ID (assuming first panitia user exists)
        $panitiaId = DB::table('users')->where('role', 'panitia')->first()?->user_id ?? 1;

        $events = [
            // Event bulan ini (Desember 2025)
            [
                'nama_kegiatan' => 'Kajian Rutin Malam Jumat',
                'description' => 'Kajian rutin setiap malam Jumat dengan tema Islam dan Kehidupan Modern. Dibawakan oleh Ustadz Muhammad Ali. Terbuka untuk umum.',
                'start_at' => Carbon::create(2025, 12, 19, 19, 30, 0),
                'end_at' => Carbon::create(2025, 12, 19, 21, 0, 0),
                'status' => 'published',
                'kuota' => 100,
                'created_by' => $panitiaId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kegiatan' => 'Santunan Anak Yatim',
                'description' => 'Program santunan untuk anak yatim dan dhuafa. Berisi pembagian sembako, pakaian, dan uang saku. Mari berbagi kebahagiaan bersama.',
                'start_at' => Carbon::create(2025, 12, 21, 8, 0, 0),
                'end_at' => Carbon::create(2025, 12, 21, 12, 0, 0),
                'status' => 'published',
                'kuota' => 50,
                'created_by' => $panitiaId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kegiatan' => 'Peringatan Maulid Nabi Muhammad SAW',
                'description' => 'Memperingati kelahiran Nabi Muhammad SAW dengan pembacaan shalawat, ceramah, dan makan bersama. Acara terbuka untuk seluruh jamaah.',
                'start_at' => Carbon::create(2025, 12, 25, 7, 0, 0),
                'end_at' => Carbon::create(2025, 12, 25, 12, 0, 0),
                'status' => 'published',
                'kuota' => 200,
                'created_by' => $panitiaId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Event bulan depan (Januari 2026)
            [
                'nama_kegiatan' => 'Tahun Baru Islam 1448 H',
                'description' => 'Peringatan pergantian tahun Hijriyah dengan doa bersama dan muhasabah. Renungkan kembali perjalanan kita dan buat resolusi tahun baru Islam.',
                'start_at' => Carbon::create(2026, 1, 7, 6, 0, 0),
                'end_at' => Carbon::create(2026, 1, 7, 9, 0, 0),
                'status' => 'published',
                'kuota' => 150,
                'created_by' => $panitiaId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kegiatan' => 'Khataman Al-Quran Juz 30',
                'description' => 'Program khataman Al-Quran bersama untuk juz 30. Setiap peserta membaca 1-2 halaman. Dilanjutkan dengan doa bersama dan makan bersama.',
                'start_at' => Carbon::create(2026, 1, 10, 15, 0, 0),
                'end_at' => Carbon::create(2026, 1, 10, 17, 30, 0),
                'status' => 'published',
                'kuota' => 60,
                'created_by' => $panitiaId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kegiatan' => 'Seminar Parenting Islami',
                'description' => 'Seminar tentang mendidik anak dengan nilai-nilai Islam di era digital. Narasumber: Dr. Hj. Fatimah Az-Zahra, M.Pd.I. Wajib hadir untuk orangtua!',
                'start_at' => Carbon::create(2026, 1, 15, 9, 0, 0),
                'end_at' => Carbon::create(2026, 1, 15, 12, 0, 0),
                'status' => 'published',
                'kuota' => 80,
                'created_by' => $panitiaId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kegiatan' => 'Bersih-Bersih Masjid Bersama',
                'description' => 'Kegiatan gotong royong membersihkan masjid dan lingkungan sekitar. Mari jaga kebersihan rumah Allah bersama-sama. Bawa peralatan kebersihan sendiri.',
                'start_at' => Carbon::create(2026, 1, 18, 7, 0, 0),
                'end_at' => Carbon::create(2026, 1, 18, 10, 0, 0),
                'status' => 'published',
                'kuota' => 40,
                'created_by' => $panitiaId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kegiatan' => 'Tarhib Ramadhan 1448 H',
                'description' => 'Persiapan menyambut bulan suci Ramadhan. Berisi kajian tentang keutamaan Ramadhan, tips ibadah, dan pembagian jadwal imam tarawih.',
                'start_at' => Carbon::create(2026, 1, 28, 19, 0, 0),
                'end_at' => Carbon::create(2026, 1, 28, 21, 0, 0),
                'status' => 'published',
                'kuota' => 120,
                'created_by' => $panitiaId,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Draft events (pending approval)
            [
                'nama_kegiatan' => 'Pengajian Akbar Bulanan',
                'description' => 'Pengajian akbar dengan mengundang ustadz ternama dari luar kota. Tema: Meningkatkan Kualitas Ibadah di Bulan Muharram.',
                'start_at' => Carbon::create(2026, 2, 1, 14, 0, 0),
                'end_at' => Carbon::create(2026, 2, 1, 17, 0, 0),
                'status' => 'draft',
                'kuota' => 250,
                'created_by' => $panitiaId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('events')->insert($events);
    }
}
