# TODO - Fitur Cetak Laporan Pelanggaran

## Phase 1: Setup Dependencies
- [ ] Install dompdf/dompdf package
- [ ] Install maatwebsite/excel package

## Phase 2: Create Controller
- [ ] Create KendaliLaporan.php controller
- [ ] Implement methods:
  - [ ] laporanPerSiswa() - View & Export
  - [ ] rekapPerKelas() - View & Export  
  - [ ] rekapPerPeriode() - View & Export
  - [ ] siswaPoinTertinggi() - View & Export
  - [ ] exportPdf() - Generic PDF export
  - [ ] exportExcel() - Generic Excel export

## Phase 3: Create Views
- [ ] Create laporan-per-siswa.blade.php
- [ ] Create laporan-rekap-kelas.blade.php
- [ ] Create laporan-rekap-periode.blade.php
- [ ] Create laporan-siswa-tertinggi.blade.php

## Phase 4: Add Routes
- [ ] Add routes for laporan pages
- [ ] Add routes for PDF/Excel exports

## Phase 5: Update Navigation
- [ ] Add "Cetak Laporan" menu to dashboard sidebar
- [ ] Add "Laporan per Siswa" submenu
- [ ] Add "Rekap per Kelas" submenu
- [ ] Add "Rekap per Periode" submenu
- [ ] Add "Siswa Poin Tertinggi" submenu

## Phase 6: Testing
- [ ] Test laporan per siswa
- [ ] Test rekap per kelas
- [ ] Test rekap per periode
- [ ] Test siswa poin tertinggi
- [ ] Test PDF export
- [ ] Test Excel export
- [ ] Test Print function

