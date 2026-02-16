# Plan: AI Chatbot untuk Landing Page SIPS

## 📋 Ringkasan Tugas
Menambahkan fitur chatbot AI di landing page yang dapat memberikan penjelasan pada pengunjung berdasarkan:
1. **Data hasil pencatatan** - Statistik pelanggaran yang ditampilkan di landing page
2. **Peraturan yang digunakan di SIPS** - Skema poin pelanggaran, kategori, dan tindakan

## 📊 Informasi yang Dikumpulkan

### File yang Relevan:
1. `resources/views/welcome.blade.php` - Landing page utama dengan statistik
2. `app/Services/AISuggestionService.php` - Service AI yang sudah ada untuk analisis pelanggaran
3. `SKEMA_POIN_PELANGGARAN.md` - Dokumentasi lengkap skema pelanggaran
4. `app/Http/Controllers/KendaliUtama.php` - Controller yang menangani landing page

### Data yang Tersedia di Landing Page:
- Total pelanggaran (ringan, sedang, berat)
- Jumlah siswa pelanggar
- Kelas yang terlibat
- Grafik tren pelanggaran 7 hari terakhir
- Tabel data pelanggaran per kelas

### Kategori Pelanggaran:
- **Ringan**: 5-15 poin (terlambat, seragam, dll)
- **Sedang**: 20-40 poin (membolos, merokok, dll)
- **Berat**: 50-100 poin (tawuran, mencuri, dll)

---

## 🎯 Plan Implementasi

### 1. Buat AI Chat Service Baru
**File:** `app/Services/SIPSChatbotService.php` ✅
- Membuat service untuk memproses pertanyaan pengguna
- Menggunakan data dari landing page dan skema pelanggaran
- Mengembalikan jawaban dalam bahasa Indonesia yang sopan dan formal

### 2. Buat Controller untuk Chatbot API
**File:** `app/Http/Controllers/ChatbotController.php` ✅
- Endpoint untuk memproses pesan pengguna
- Mengambil data real-time dari database
- Mengembalikan response JSON

### 3. Tambah Routes untuk Chatbot
**File:** `routes/web.php` ✅
- Route API untuk chatbot

### 4. Update Landing Page dengan UI Chatbot
**File:** `resources/views/welcome.blade.php` ✅
- Tambah floating chatbot button
- Tambah chat window dengan UI yang menarik
- Integrasi dengan API chatbot

---

## 📁 File yang Diedit/Dibuat

| No | File | Aksi |
|----|------|------|
| 1 | `app/Services/SIPSChatbotService.php` | Dibuat (Baru) ✅ |
| 2 | `app/Http/Controllers/ChatbotController.php` | Dibuat (Baru) ✅ |
| 3 | `routes/web.php` | Diedit ✅ |
| 4 | `resources/views/welcome.blade.php` | Diedit ✅ |

---

## 💬 Topik yang Dapat Dijawab Chatbot

### Kategori Informasi:
1. **Tentang SIPS**
   - Apa itu SIPS?
   - Bagaimana cara menggunakan sistem?

2. **Kategori Pelanggaran**
   - Apa saja kategori pelanggaran?
   - Apa perbedaan ringan, sedang, berat?

3. **Sistem Poin**
   - Berapa poin untuk pelanggaran tertentu?
   - Apa dampak dari akumulasi poin?

4. **Tindakan Berdasarkan Poin**
   - Apa yang terjadi jika poin siswa mencapai 25?
   - Kapan siswa akan diproses PTOS?

5. **Data Statistik**
   - Berapa total pelanggaran hari ini?
   - Bagaimana tren pelanggaran terbaru?

---

## ✅ Tahapan Implementasi

1. [x] Buat `SIPSChatbotService.php` dengan logika pemrosesan pertanyaan
2. [x] Buat `ChatbotController.php` untuk handling API requests
3. [x] Tambahkan route untuk chatbot di `web.php`
4. [x] Update `welcome.blade.php` dengan UI chatbot (floating button + chat window)
5. [x] Test chatbot functionality

---

## Catatan:
- Chatbot menggunakan bahasa Indonesia yang sopan dan formal
- Informasi yang diberikan cukup detail dan mudah dipahami
- Chatbot dapat menjawab pertanyaan tentang regulasi dan data pelanggaran

