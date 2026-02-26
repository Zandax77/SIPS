# TODO: Print & PDF Export for BK Guru

## Phase 1: Setup & Installation
- [x] 1. Install dompdf package: `composer require dompdf/dompdf`

## Phase 2: Routes Configuration  
- [x] 2. Add new routes in `routes/web.php`
   - Print violation history route
   - PDF export violation route
   - Print action history route
   - PDF export action route

## Phase 3: Controller Methods
- [x] 3. Add controller methods in `app/Http/Controllers/KendaliSiswa.php`
   - `cetakPelanggaran()` - Generate printable HTML
   - `exportPelanggaranPdf()` - Generate PDF
   - `cetakTindakan()` - Generate printable HTML
   - `exportTindakanPdf()` - Generate PDF

## Phase 4: View Updates
- [x] 4. Add export buttons in `resources/views/siswa-poin-detail.blade.php`
   - Add buttons in "Riwayat Pelanggaran" section
   - Add buttons in "Riwayat Tindakan" section

## Phase 5: PDF Templates
- [x] 5. Create PDF template: `resources/views/pdf/pelanggaran.blade.php`
- [x] 6. Create PDF template: `resources/views/pdf/tindakan.blade.php`

## Phase 6: Testing
- [ ] 7. Test print functionality in browser
- [ ] 8. Test PDF export download

