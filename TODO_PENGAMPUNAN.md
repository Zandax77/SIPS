# Plan: Fitur Pengampunan dan Pengurangan Poin Pelanggaran

## Information Gathered

### Current System Analysis:
1. **Pelanggaran Table**: Stores violation records with `id_siswa`, `id_jenis_pelanggaran`, `deskripsi`, `id_petugas`, `lampiran`, `tipe_lampiran`
2. **Kategori Pelanggaran**: Contains point values (Ringan, Sedang, Berat with different points)
3. **Siswa Detail Page**: Shows violation history and total points
4. **Existing Controllers**: `KendaliPelanggaran` for recording violations, `KendaliSiswa` for viewing student details

### Requirements:
- Create feature to record **pengampunan pelanggaran** (forgiving/removing violations)
- Create feature to record **pengurangan poin pelanggaran** (reducing violation points)
- Track who performed the action and the reason
- Display history of forgiveness/reduction on student detail page

---

## Implementation Completed

### Step 1: Database Migration ✅
- Created `database/migrations/2026_02_16_000000_create_pengampunan_pelanggarans_table.php`
- Table fields: id_pelanggaran, id_siswa, id_petugas, tipe, poin_asli, poin_dikurangi, alasan, timestamps

### Step 2: Model ✅
- Created `app/Models/PengampunanPelanggaran.php` with relationships

### Step 3: Controller Methods ✅
Added to `app/Http/Controllers/KendaliPelanggaran.php`:
- `getDetailPelanggaran($id)` - Get violation detail for action
- `simpanPengampunan(Request $request)` - Save full forgiveness
- `simpanPenguranganPoin(Request $request)` - Save partial point reduction
- `getRiwayatPengampunan($id_siswa)` - Get history for student

### Step 4: Routes ✅
Added in `routes/web.php`:
- `GET /api/pelanggaran/{id}/detail`
- `POST /pelanggaran/pengampunan`
- `POST /pelanggaran/pengurangan-poin`
- `GET /api/pelanggaran/riwayat-pengampunan/{id_siswa}`

### Step 5: View Updates ✅
- Updated `resources/views/siswa-poin-detail.blade.php`:
  - Added "Tindakan" button in violation table
  - Added Tindakan Modal with forms
  - Added tab switching between Pengampunan and Pengurangan Poin
  - Added riwayat (history) display

### Step 6: Point Calculation ✅
- Updated `app/Http/Controllers/KendaliSiswa.php`:
  - Added pengampunan records in point calculation
  - Adjusted totalPoin considering forgiven and reduced points

---

## Migration Status
✅ Executed: `php artisan migrate`
- 2026_02_16_000000_create_pengampunan_pelanggarans_table - DONE

---

## Usage

1. Go to Student Detail page (Data Siswa & Poin → Klik siswa)
2. In violation table, click "Tindakan" button
3. Choose action:
   - **Pengampunan**: Forgives entire violation (removes all points)
   - **Pengurangan Poin**: Reduces violation points partially
4. Enter reason and submit
5. Total points will be recalculated automatically

