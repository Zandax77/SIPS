# TODO - PDF Download Fix

## Task: Fix PDF download not showing data

### Steps:
- [x] 1. Fix `exportPdfLaporanKelas()` in KendaliPelanggaran.php
- [x] 2. Fix `exportPelanggaranPdf()` in KendaliSiswa.php
- [x] 3. Fix `exportTindakanPdf()` in KendaliSiswa.php

### Issue:
The PDF download functions are not properly rendering the HTML view. The fix involves:
1. Storing the rendered HTML in a variable first
2. Fixing the order of Dompdf method calls (setPaper should be before render)

### Status: COMPLETED

