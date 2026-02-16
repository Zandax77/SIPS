# Plan: Fitur Lupa Password dengan Notifikasi ke Super Admin

## Status: SELESAI DIIMPLEMENTASIKAN

## Fitur yang Ditambahkan:

### 1. **Halaman Lupa Password** (`/forgot-password`)
- Petugas dapat mengklik "Lupa password?" di halaman login
- Memasukkan email untuk meminta reset password
- Token reset password disimpan di tabel petugas

### 2. **Notifikasi ke Super Admin (TAMPIL DI DASHBOARD)**
- Ketika petugas meminta reset password, Super Admin akan melihat notifikasi di halaman Dashboard
- Notifikasi menampilkan: nama petugas, email, jabatan, dan waktu permintaan
- Ada tombol "Reset Password" yang langsung ke halaman Kelola Petugas

### 3. **Reset Password di Panel Admin** (`/admin/petugas`)
- Super Admin dapat mereset password petugas ke "123456"
- Tombol reset password (ikon refresh biru) di tabel kelola petugas

### 4. **Ganti Password Mandiri** (`/change-password`)
- Menu "Ubah Password" di sidebar dashboard
- Petugas dapat mengganti password secara mandiri

## File yang Dibuat/Diedit:

1. **Database Migration**: `database/migrations/2026_02_20_000000_add_password_reset_to_petugas_table.php`
2. **Notification Class**: `app/Notifications/ResetPasswordRequestNotification.php`
3. **Petugas Model**: `app/Models/Petugas.php`
4. **KendaliUtama Controller**: `app/Http/Controllers/KendaliUtama.php`
5. **KendaliAdmin Controller**: `app/Http/Controllers/KendaliAdmin.php`
6. **Routes**: `routes/web.php`
7. **Views**:
   - `resources/views/forgot-password.blade.php` (baru)
   - `resources/views/change-password.blade.php` (baru)
   - `resources/views/login.blade.php`
   - `resources/views/kelola-petugas.blade.php`
   - `resources/views/dashboard.blade.php`

## Cara Penggunaan:

1. **Petugas lupa password**:
   - Klik "Lupa password?" di halaman login
   - Masukkan email → Token reset disimpan di database

2. **Super Admin melihat notifikasi**:
   - Login sebagai admin
   - Di Dashboard akan muncul notifikasi permintaan reset password (jika ada)
   - Klik "Reset Password" atau buka menu Kelola Petugas

3. **Super Admin mereset password**:
   - Klik ikon refresh biru pada baris petugas
   - Password direset menjadi "123456"

4. **Petugas ganti password**:
   - Login dengan password "123456"
   - Buka menu "Ubah Password" di sidebar
   - Masukkan password lama dan password baru

## Catatan Penting:

- Pastikan sudah menjalankan migration: `php artisan migrate`
- Clear cache: `php artisan cache:clear` dan `php artisan view:clear`

