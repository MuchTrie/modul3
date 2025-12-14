<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'username' => 'admin',
            'nama_lengkap' => 'Administrator Masjid',
            'email' => 'admin@masjid.com',
            'no_hp' => '081234567890',
            'alamat' => 'Masjid Al-Nassr',
            'role' => 'admin',
            'status_aktif' => 'aktif',
            'password' => bcrypt('admin123'),
        ]);

        // Create Pengurus User (DKM)
        User::create([
            'username' => 'dkm',
            'nama_lengkap' => 'Ketua DKM',
            'email' => 'dkm@masjid.com',
            'no_hp' => '081234567891',
            'alamat' => 'Masjid Al-Nassr',
            'role' => 'pengurus',
            'status_aktif' => 'aktif',
            'password' => bcrypt('dkm123'),
        ]);

        // Create Pengurus User (Panitia)
        User::create([
            'username' => 'panitia',
            'nama_lengkap' => 'Panitia Event',
            'email' => 'panitia@masjid.com',
            'no_hp' => '081234567892',
            'alamat' => 'Masjid Al-Nassr',
            'role' => 'panitia',
            'status_aktif' => 'aktif',
            'password' => bcrypt('panitia123'),
        ]);

        // Create Jemaah User
        User::create([
            'username' => 'jamaah',
            'nama_lengkap' => 'Ahmad Jamaah',
            'email' => 'jamaah@masjid.com',
            'no_hp' => '081234567893',
            'alamat' => 'Jl. Contoh No. 123',
            'role' => 'jemaah',
            'status_aktif' => 'aktif',
            'password' => bcrypt('jamaah123'),
        ]);

        // Create additional Jemaah
        User::create([
            'username' => 'budi',
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'no_hp' => '081234567894',
            'alamat' => 'Jl. Melati No. 45',
            'role' => 'jemaah',
            'status_aktif' => 'aktif',
            'password' => bcrypt('password'),
        ]);

        // Seed Events
        $this->call([
            EventSeeder::class,
        ]);
    }
}
