# Plan: Perbaikan Saran AI dengan Analisis Komprehensif Pelanggaran

## Tujuan
Memperbaiki sistem saran AI dengan menganalisis:
1. **Nama pelanggaran** - nama spesifik jenis pelanggaran (contoh: "Membuang Sampah Sembarangan")
2. **Jenis pelanggaran** - kategori (Ringan, Sedang, Berat)
3. **Deskripsi pelanggaran** - konteks tambahan dari pelanggaran
4. **Jumlah pelanggaran serupa** - frekuensi pelanggaran yang sama/similar

## Files yang Perlu Diedit

### 1. app/Services/AISuggestionService.php
- [x] Analisis frekuensi setiap nama pelanggaran spesifik
- [x] Deteksi pelanggaran berulang (repeat violations)
- [x] Ekstrak pola dari deskripsi pelanggaran
- [x] Generate personalized recommendations berdasarkan pola pelanggaran spesifik
- [x] Tambahkan insight tentang pelanggaran berulang dengan nama spesifik

### 2. resources/views/siswa-poin-detail.blade.php
- [x] Tampilkan nama pelanggaran tertinggi
- [x] Tampilkan warning pelanggaran berulang
- [x] Tampilkan kata kunci dari deskripsi

## ✅ COMPLETED

