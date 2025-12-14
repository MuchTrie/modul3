<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. JABATAN (independent, self-referencing)
        Schema::create('jabatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jabatan', 100);
            $table->text('deskripsi')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('jabatan');
            $table->integer('level')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->timestamp('deleted_at')->nullable();
        });

        // 2. DANA_DKM (independent)
        Schema::create('dana_dkm', function (Blueprint $table) {
            $table->id('ID_DKM');
            $table->string('Sumber_dana', 255)->nullable();
            $table->decimal('Jumlah_dana', 10, 2)->nullable();
            $table->date('Tanggal_masuk')->nullable();
            $table->text('Keterangan')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 3. MUZAKKI (independent)
        Schema::create('muzakki', function (Blueprint $table) {
            $table->id('id_muzakki');
            $table->string('nama', 255);
            $table->text('alamat')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('password', 255)->nullable();
            $table->timestamp('tgl_daftar')->useCurrent();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 4. MUSTAHIK (independent)
        Schema::create('mustahik', function (Blueprint $table) {
            $table->id('id_mustahik');
            $table->string('nama', 255);
            $table->text('alamat')->nullable();
            $table->string('kategori_mustahik', 50)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('surat_dtks', 255)->nullable();
            $table->enum('status', ['aktif', 'non-aktif'])->default('aktif');
            $table->string('password', 255)->nullable();
            $table->timestamp('tgl_daftar')->useCurrent();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 5. JADWAL_SHOLAT (independent)
        Schema::create('jadwal_sholat', function (Blueprint $table) {
            $table->id();
            $table->time('subuh')->nullable();
            $table->time('dzuhur')->nullable();
            $table->time('ashar')->nullable();
            $table->time('maghrib')->nullable();
            $table->time('isya')->nullable();
            $table->date('tanggal')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 6. INVENTARIS (independent)
        Schema::create('inventaris', function (Blueprint $table) {
            $table->id('id_asset');
            $table->string('nama_asset', 255);
            $table->string('jenis_asset', 100)->nullable();
            $table->enum('kondisi', ['baik', 'rusak', 'perlu perbaikan'])->default('baik');
            $table->year('tahun_peroleh')->nullable();
            $table->enum('status', ['aktif', 'tidak aktif', 'dihapus'])->default('aktif');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 7. ARTIKEL (depends on users)
        Schema::create('artikel', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 255);
            $table->text('isi')->nullable();
            $table->string('penulis', 255)->nullable();
            $table->date('tanggal')->nullable();
            $table->foreignId('author_id')->constrained('users');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 8. BERITA (depends on users)
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 255);
            $table->text('isi')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->foreignId('author_id')->constrained('users');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 9. PENGUMUMAN (depends on users)
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 255);
            $table->text('isi')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('kategori', 50)->nullable();
            $table->foreignId('author_id')->constrained('users');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 10. NOTIFIKASI (optional FK to users)
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->text('pesan');
            $table->enum('metode', ['email', 'whatsapp', 'push'])->default('push');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamp('waktu_kirim')->nullable();
            $table->foreignId('target_jemaah_id')->nullable()->constrained('users');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 11. DANA_OPERASIONAL (depends on dana_dkm, users)
        Schema::create('dana_operasional', function (Blueprint $table) {
            $table->id('ID_Operasional');
            $table->string('Keperluan', 255)->nullable();
            $table->decimal('Jumlah_Pengeluaran', 10, 2)->nullable();
            $table->date('Tanggal_Pengeluaran')->nullable();
            $table->text('Keterangan')->nullable();
            $table->foreignId('ID_DKM')->nullable()->constrained('dana_dkm', 'ID_DKM');
            $table->foreignId('ID_User')->nullable()->constrained('users');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 12. JADWAL_PENYEMBELIH (depends on dana_operasional)
        Schema::create('jadwal_penyembelih', function (Blueprint $table) {
            $table->id('ID_Jadwal');
            $table->string('Foto_Dokumentasi', 255)->nullable();
            $table->string('Nama_Penyembelih', 255)->nullable();
            $table->timestamp('Waktu_Penyembelih')->nullable();
            $table->string('Lokasi_Penyembelih', 255)->nullable();
            $table->foreignId('ID_Operasional')->nullable()->constrained('dana_operasional', 'ID_Operasional');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 13. HEWAN_KURBAN (depends on jadwal_penyembelih, users)
        Schema::create('hewan_kurban', function (Blueprint $table) {
            $table->id('ID_Hewan');
            $table->foreignId('ID_Jadwal')->constrained('jadwal_penyembelih', 'ID_Jadwal');
            $table->string('Jenis_Hewan', 50)->nullable();
            $table->string('Status_Hewan', 20)->nullable();
            $table->foreignId('ID_User')->nullable()->constrained('users');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 14. PENERIMA_KURBAN (depends on users)
        Schema::create('penerima_kurban', function (Blueprint $table) {
            $table->id('ID_Penerima');
            $table->string('Nama', 255);
            $table->string('Tempat_Tinggal', 255)->nullable();
            $table->date('Tanggal_Terima')->nullable();
            $table->foreignId('ID_User')->nullable()->constrained('users');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 15. DISTRIBUSI_DAGING (depends on hewan_kurban, penerima_kurban)
        Schema::create('distribusi_daging', function (Blueprint $table) {
            $table->id('ID_Distribusi');
            $table->foreignId('ID_Hewan')->constrained('hewan_kurban', 'ID_Hewan');
            $table->foreignId('ID_Penerima')->constrained('penerima_kurban', 'ID_Penerima');
            $table->date('Tanggal_Distribusi')->nullable();
            $table->string('Penerima', 255)->nullable();
            $table->string('Dokumentasi', 255)->nullable();
            $table->string('Status_Distribusi', 20)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 16. ZIS_MASUK (depends on muzakki)
        Schema::create('zis_masuk', function (Blueprint $table) {
            $table->id('id_zis');
            $table->foreignId('id_muzakki')->constrained('muzakki', 'id_muzakki');
            $table->date('tgl_masuk')->nullable();
            $table->enum('jenis_zis', ['zakat', 'infaq', 'shadaqah', 'wakaf'])->nullable();
            $table->decimal('jumlah', 10, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 17. PENYALURAN (depends on zis_masuk, mustahik)
        Schema::create('penyaluran', function (Blueprint $table) {
            $table->id('id_penyaluran');
            $table->foreignId('id_zis')->constrained('zis_masuk', 'id_zis');
            $table->date('tgl_salur')->nullable();
            $table->decimal('jumlah', 10, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('id_mustahik')->constrained('mustahik', 'id_mustahik');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 18. PETUGAS_ZIS (depends on users)
        Schema::create('petugas_zis', function (Blueprint $table) {
            $table->id('id_petugas_zis');
            $table->string('nama', 255);
            $table->text('alamat')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('password', 255)->nullable();
            $table->timestamp('tgl_daftar')->useCurrent();
            $table->foreignId('id_user')->nullable()->constrained('users');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 19. PERAWATAN (depends on inventaris)
        Schema::create('perawatan', function (Blueprint $table) {
            $table->id('id_perawatan');
            $table->foreignId('id_asset')->constrained('inventaris', 'id_asset');
            $table->date('tanggal')->nullable();
            $table->string('jenis_perawatan', 100)->nullable();
            $table->enum('status', ['sedang berjalan', 'selesai', 'ditunda'])->default('sedang berjalan');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 20. LAPORAN_PERAWATAN (depends on perawatan, inventaris, users)
        Schema::create('laporan_perawatan', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->foreignId('id_perawatan')->constrained('perawatan', 'id_perawatan');
            $table->foreignId('id_asset')->constrained('inventaris', 'id_asset');
            $table->foreignId('id_user')->constrained('users');
            $table->date('tanggal_laporan')->nullable();
            $table->text('hasil')->nullable();
            $table->enum('status_barang', ['siap pakai', 'perlu perbaikan lanjutan', 'tidak bisa diperbaiki'])->default('siap pakai');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 21. RIWAYAT_JABATAN (depends on users, jabatan)
        Schema::create('riwayat_jabatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengurus_id')->constrained('users');
            $table->foreignId('jabatan_id')->constrained('jabatan');
            $table->date('periode_awal')->nullable();
            $table->date('periode_akhir')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 22. TUGAS_TANGGUNG_JAWAB (depends on jabatan)
        Schema::create('tugas_tanggung_jawab', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jabatan_id')->constrained('jabatan');
            $table->string('nama_tugas', 150);
            $table->text('deskripsi_tugas')->nullable();
            $table->enum('prioritas', ['tinggi', 'sedang', 'rendah'])->default('sedang');
            $table->timestamp('tanggal_ditambahkan')->useCurrent();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 23. PENUGASAN_TUGAS (depends on tugas_tanggung_jawab, users)
        Schema::create('penugasan_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas_tanggung_jawab');
            $table->foreignId('pengurus_id')->constrained('users');
            $table->date('tanggal_ditugaskan');
            $table->enum('status', ['belum mulai', 'dalam pengerjaan', 'selesai'])->default('belum mulai');
            $table->text('catatan')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 24. JADWAL_PIKET (depends on users)
        Schema::create('jadwal_piket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengurus_id')->constrained('users');
            $table->date('tanggal_tugas');
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('lokasi_tugas', 100)->nullable();
            $table->string('jenis_tugas', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status_tugas', ['belum mulai', 'dalam pengerjaan', 'selesai'])->default('belum mulai');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 25. PENGAJUAN_EVENT (depends on users)
        Schema::create('pengajuan_event', function (Blueprint $table) {
            $table->id('pengajuan_event_id');
            $table->string('judul', 255);
            $table->text('deskripsi')->nullable();
            $table->text('rule_usulan')->nullable();
            $table->date('tgl_mulai_usulan')->nullable();
            $table->date('tgl_selesai_usulan')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan')->nullable();
            $table->foreignId('jemaah_id')->constrained('users');
            $table->foreignId('diinput_oleh_jemaah_id')->constrained('users');
            $table->foreignId('approved_by_jemaah_id')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 26. SESI_EVENT (depends on events)
        Schema::create('sesi_event', function (Blueprint $table) {
            $table->id('sesi_event_id');
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->string('title_override', 255)->nullable();
            $table->string('location_override', 255)->nullable();
            $table->boolean('published')->default(false);
            $table->text('meta')->nullable();
            $table->foreignId('event_id')->constrained('events', 'event_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });

        // 27. PESERTA_EVENT (depends on users, sesi_event)
        Schema::create('peserta_event', function (Blueprint $table) {
            $table->id('peserta_event_id');
            $table->enum('status_daftar', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamp('registered_at')->nullable();
            $table->enum('status_hadir', ['hadir', 'tidak hadir', 'belum diketahui'])->default('belum diketahui');
            $table->timestamp('checkin_at')->nullable();
            $table->timestamp('checkout_at')->nullable();
            $table->timestamp('marked_at')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('jemaah_id')->constrained('users');
            $table->foreignId('sesi_event_id')->constrained('sesi_event', 'sesi_event_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop dalam urutan terbalik (child tables dulu)
        Schema::dropIfExists('peserta_event');
        Schema::dropIfExists('sesi_event');
        Schema::dropIfExists('pengajuan_event');
        Schema::dropIfExists('jadwal_piket');
        Schema::dropIfExists('penugasan_tugas');
        Schema::dropIfExists('tugas_tanggung_jawab');
        Schema::dropIfExists('riwayat_jabatan');
        Schema::dropIfExists('laporan_perawatan');
        Schema::dropIfExists('perawatan');
        Schema::dropIfExists('petugas_zis');
        Schema::dropIfExists('penyaluran');
        Schema::dropIfExists('zis_masuk');
        Schema::dropIfExists('distribusi_daging');
        Schema::dropIfExists('penerima_kurban');
        Schema::dropIfExists('hewan_kurban');
        Schema::dropIfExists('jadwal_penyembelih');
        Schema::dropIfExists('dana_operasional');
        Schema::dropIfExists('notifikasi');
        Schema::dropIfExists('pengumuman');
        Schema::dropIfExists('berita');
        Schema::dropIfExists('artikel');
        Schema::dropIfExists('inventaris');
        Schema::dropIfExists('jadwal_sholat');
        Schema::dropIfExists('mustahik');
        Schema::dropIfExists('muzakki');
        Schema::dropIfExists('dana_dkm');
        Schema::dropIfExists('jabatan');
    }
};
