<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($prestasi) ? 'Edit' : 'Tambah' }} Prestasi - SIPS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        indigo: { 50: '#eef2ff', 100: '#e0e7ff', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca' },
                        emerald: { 500: '#10b981', 600: '#059669' },
                        amber: { 500: '#f59e0b', 600: '#d97706' },
                        rose: { 500: '#f43f5e', 600: '#e11d48' },
                        purple: { 50: '#faf5ff', 100: '#f3e8ff', 500: '#a855f7', 600: '#9333ea', 700: '#7e22ce' },
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fade-in 0.4s ease-out forwards; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen">
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-indigo-100 rounded-full opacity-40 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-100 rounded-full opacity-40 blur-3xl"></div>
    </div>

    <div class="relative min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white/80 backdrop-blur-sm border-b border-gray-100 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('prestasi.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-purple-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span class="hidden sm:inline">Kembali</span>
                        </a>
                        <div class="w-px h-8 bg-gray-200"></div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-600 flex items-center justify-center shadow-lg shadow-purple-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-xl font-bold text-gray-800">SIPS</span>
                                <p class="text-xs text-gray-500">{{ isset($prestasi) ? 'Edit Prestasi' : 'Tambah Prestasi' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-3 pl-4 border-l border-gray-200">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-semibold text-gray-800">{{ $namaPetugas }}</p>
                                <p class="text-xs text-gray-500">{{ $jabatan }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="flex-1 max-w-4xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Error Messages -->
            @if($errors->any())
                <div class="mb-6 bg-rose-50 border border-rose-200 rounded-xl p-4 animate-fade-in">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-rose-800">Terdapat kesalahan pengisian:</p>
                            <ul class="mt-1 text-sm text-rose-600 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 animate-fade-in">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-white rounded-t-2xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">{{ isset($prestasi) ? 'Edit Prestasi' : 'Form Tambah Prestasi' }}</h2>
                            <p class="text-sm text-gray-500">Catat prestasi siswa</p>
                        </div>
                    </div>
                </div>

                <form action="{{ isset($prestasi) ? route('prestasi.update', $prestasi->id) : route('prestasi.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @if(isset($prestasi))
                        @method('PUT')
                    @endif

                    <div class="space-y-6">
                        <!-- Pilih Siswa -->
                        <div class="bg-purple-50 rounded-xl p-5 border border-purple-200">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Pilih Siswa <span class="text-rose-500">*</span></label>
                            
                            @if(isset($prestasi))
                                <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200">
                                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                        <span class="text-sm font-bold text-purple-600">{{ substr($selectedSiswa->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $selectedSiswa->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $selectedSiswa->nis }} - {{ $selectedSiswa->kelas }}</p>
                                    </div>
                                </div>
                                <input type="hidden" name="id_siswa" value="{{ $prestasi->id_siswa }}">
                            @elseif($selectedSiswa)
                                <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200">
                                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                        <span class="text-sm font-bold text-purple-600">{{ substr($selectedSiswa->nama_siswa, 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">{{ $selectedSiswa->nama_siswa }}</p>
                                        <p class="text-sm text-gray-500">{{ $selectedSiswa->nis }} - {{ $selectedSiswa->kelas }}</p>
                                    </div>
                                    <a href="{{ route('prestasi.create') }}" class="text-sm text-rose-600 hover:text-rose-700 font-medium">Ganti</a>
                                </div>
                                <input type="hidden" name="id_siswa" value="{{ $selectedSiswa->id }}">
                            @else
                                <div class="space-y-3">
                                    <div class="relative">
                                        <input type="text" id="searchSiswa" name="search_siswa" value="{{ $searchSiswa ?? '' }}" placeholder="Cari nama siswa atau NIS..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all outline-none" autocomplete="off">
                                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>

                                    <div id="siswaResults" class="space-y-2">
                                        @if(count($siswaResult) > 0)
                                            @foreach($siswaResult as $siswa)
                                                <div class="siswa-item flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200 hover:border-purple-300 hover:bg-purple-50/50 cursor-pointer transition-all" data-id="{{ $siswa->id }}" data-name="{{ $siswa->nama_siswa }}" data-nis="{{ $siswa->nis }}" data-kelas="{{ $siswa->kelas }}">
                                                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                                        <span class="text-sm font-bold text-purple-600">{{ substr($siswa->nama_siswa, 0, 1) }}</span>
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-gray-800">{{ $siswa->nama_siswa }}</p>
                                                        <p class="text-sm text-gray-500">{{ $siswa->nis }} - {{ $siswa->kelas }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @elseif($searchSiswa)
                                            <p class="text-sm text-gray-500 text-center py-3">Siswa tidak ditemukan</p>
                                        @else
                                            <p class="text-sm text-gray-400 text-center py-3">Ketik nama atau NIS untuk mencari siswa</p>
                                        @endif
                                    </div>

                                    <input type="hidden" name="id_siswa" id="selectedSiswaId" value="">
                                </div>
                            @endif
                        </div>

                        <!-- Nama Prestasi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Prestasi <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_prestasi" value="{{ old('nama_prestasi', isset($prestasi) ? $prestasi->nama_prestasi : '') }}" placeholder="Contoh: Juara Olimpiade Matematika" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all outline-none">
                        </div>

                        <!-- Bidang dan Tingkat -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bidang <span class="text-rose-500">*</span></label>
                                <select name="bidang" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all outline-none bg-white">
                                    <option value="">-- Pilih Bidang --</option>
                                    <option value="Akademik" {{ old('bidang', isset($prestasi) ? $prestasi->bidang : '') == 'Akademik' ? 'selected' : '' }}>Akademik</option>
                                    <option value="Non Akademik" {{ old('bidang', isset($prestasi) ? $prestasi->bidang : '') == 'Non Akademik' ? 'selected' : '' }}>Non Akademik</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat <span class="text-rose-500">*</span></label>
                                <select name="tingkat" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all outline-none bg-white">
                                    <option value="">-- Pilih Tingkat --</option>
                                    <option value="Sekolah" {{ old('tingkat', isset($prestasi) ? $prestasi->tingkat : '') == 'Sekolah' ? 'selected' : '' }}>Sekolah</option>
                                    <option value="Kecamatan" {{ old('tingkat', isset($prestasi) ? $prestasi->tingkat : '') == 'Kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                                    <option value="Kabupaten" {{ old('tingkat', isset($prestasi) ? $prestasi->tingkat : '') == 'Kabupaten' ? 'selected' : '' }}>Kabupaten</option>
                                    <option value="Provinsi" {{ old('tingkat', isset($prestasi) ? $prestasi->tingkat : '') == 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                                    <option value="Nasional" {{ old('tingkat', isset($prestasi) ? $prestasi->tingkat : '') == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                                </select>
                            </div>
                        </div>

                        <!-- Peringkat -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Peringkat / Posisi</label>
                            <input type="text" name="peringkat" value="{{ old('peringkat', isset($prestasi) ? $prestasi->peringkat : '') }}" placeholder="Contoh: Juara 1, Harapan 2, Peserta Terbaik..." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all outline-none">
                        </div>

                        <!-- Pengurangan Poin -->
                        <div class="bg-emerald-50 rounded-xl p-5 border border-emerald-200">
                            <label class="block text-sm font-medium text-emerald-800 mb-2">Pengurangan Poin Pelanggaran <span class="text-rose-500">*</span></label>
                            <p class="text-xs text-emerald-600 mb-3">Jumlah poin pelanggaran siswa yang akan dikurangi karena prestasi ini. (Maks: 100)</p>
                            <div class="flex items-center gap-3">
                                <input type="number" name="pengurangan_poin" value="{{ old('pengurangan_poin', isset($prestasi) ? $prestasi->pengurangan_poin : '0') }}" min="0" max="100" required class="w-32 px-4 py-3 rounded-xl border border-emerald-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all outline-none text-center text-lg font-bold">
                                <span class="text-sm font-medium text-emerald-700">Poin</span>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi / Keterangan</label>
                            <textarea name="deskripsi" rows="3" placeholder="Deskripsi tambahan tentang prestasi..." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all outline-none resize-none">{{ old('deskripsi', isset($prestasi) ? $prestasi->deskripsi : '') }}</textarea>
                        </div>

                        <!-- Upload Bukti -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti (Sertifikat/Foto)</label>
                            <div class="mt-1 flex items-start gap-4">
                                <div class="flex-1">
                                    <input type="file" id="bukti_foto" name="bukti_foto" accept="image/jpeg,image/jpg,image/png,image/gif,application/pdf" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all outline-none file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                    <p class="mt-1 text-xs text-gray-400">Format: JPEG, JPG, PNG, GIF, PDF. Maks: 2MB</p>
                                </div>
                                <div id="filePreview" class="hidden flex-shrink-0">
                                    <div class="relative w-20 h-20 rounded-xl border border-gray-200 overflow-hidden bg-gray-50">
                                        <img id="previewImage" src="" alt="Preview" class="w-full h-full object-cover hidden">
                                        <div id="fileIconPlaceholder" class="w-full h-full flex items-center justify-center hidden">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <button type="button" onclick="removeFile()" class="absolute -top-2 -right-2 w-5 h-5 bg-rose-500 text-white rounded-full flex items-center justify-center shadow hover:bg-rose-600 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            @if(isset($prestasi) && $prestasi->bukti_foto)
                                <div class="mt-3 p-3 bg-purple-50 rounded-lg border border-purple-200">
                                    <p class="text-sm text-purple-700 font-medium">File saat ini:</p>
                                    @php
                                        $extension = pathinfo($prestasi->bukti_foto, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                                    @endphp
                                    <div class="flex items-center gap-3 mt-2">
                                        @if($isImage)
                                            <img src="{{ asset('storage/' . $prestasi->bukti_foto) }}" alt="Current" class="w-16 h-16 rounded-lg object-cover border border-gray-200">
                                        @else
                                            <div class="w-16 h-16 rounded-lg bg-purple-100 flex items-center justify-center border border-gray-200">
                                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm text-gray-600">{{ $prestasi->bukti_foto }}</p>
                                            <p class="text-xs text-gray-400">Upload file baru untuk mengganti</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Tanggal Prestasi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Prestasi <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_prestasi" value="{{ old('tanggal_prestasi', isset($prestasi) ? $prestasi->tanggal_prestasi : date('Y-m-d')) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all outline-none">
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex gap-3 mt-8">
                        <a href="{{ route('prestasi.index') }}" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium text-center">
                            Batal
                        </a>
                        <button type="submit" class="flex-1 px-4 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors font-medium">
                            {{ isset($prestasi) ? 'Simpan Perubahan' : 'Simpan Prestasi' }}
                        </button>
                    </div>
                </form>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white/60 backdrop-blur-sm border-t border-gray-100 py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} SIPS. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <script>
        // File upload preview
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('bukti_foto');
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (!file) {
                        document.getElementById('filePreview').classList.add('hidden');
                        return;
                    }

                    const preview = document.getElementById('filePreview');
                    const previewImage = document.getElementById('previewImage');
                    const fileIcon = document.getElementById('fileIconPlaceholder');

                    preview.classList.remove('hidden');

                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            previewImage.src = ev.target.result;
                            previewImage.classList.remove('hidden');
                            fileIcon.classList.add('hidden');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        previewImage.classList.add('hidden');
                        previewImage.src = '';
                        fileIcon.classList.remove('hidden');
                    }
                });
            }
        });

        function removeFile() {
            const fileInput = document.getElementById('bukti_foto');
            fileInput.value = '';
            document.getElementById('filePreview').classList.add('hidden');
            document.getElementById('previewImage').classList.add('hidden');
            document.getElementById('previewImage').src = '';
            document.getElementById('fileIconPlaceholder').classList.remove('hidden');
        }

        // Student selection from search results
        document.querySelectorAll('.siswa-item').forEach(item => {
            item.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const nis = this.dataset.nis;
                const kelas = this.dataset.kelas;

                document.getElementById('selectedSiswaId').value = id;
                document.getElementById('searchSiswa').value = name + ' (' + nis + ' - ' + kelas + ')';
                document.getElementById('searchSiswa').disabled = true;

                // Highlight selected
                document.querySelectorAll('.siswa-item').forEach(el => {
                    el.classList.remove('border-purple-500', 'bg-purple-50');
                });
                this.classList.add('border-purple-500', 'bg-purple-50');

                document.getElementById('siswaResults').innerHTML = `
                    <div class="flex items-center gap-2 text-sm text-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Siswa dipilih: <strong>${name}</strong> (${nis} - ${kelas})</span>
                        <button type="button" onclick="resetSiswaSearch()" class="text-rose-500 hover:text-rose-700 ml-2">Ubah</button>
                    </div>
                `;
            });
        });

        function resetSiswaSearch() {
            document.getElementById('selectedSiswaId').value = '';
            document.getElementById('searchSiswa').value = '';
            document.getElementById('searchSiswa').disabled = false;
            document.getElementById('siswaResults').innerHTML = `<p class="text-sm text-gray-400 text-center py-3">Ketik nama atau NIS untuk mencari siswa</p>`;
            document.getElementById('searchSiswa').focus();
        }

        // Auto search as user types
        let searchTimeout;
        document.getElementById('searchSiswa')?.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            if (query.length < 1) {
                document.getElementById('siswaResults').innerHTML = `<p class="text-sm text-gray-400 text-center py-3">Ketik nama atau NIS untuk mencari siswa</p>`;
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`{{ route('api.prestasi.search-siswa') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        let html = '';
                        if (data.length > 0) {
                            data.forEach(siswa => {
                                html += `
                                    <div class="siswa-item flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200 hover:border-purple-300 hover:bg-purple-50/50 cursor-pointer transition-all" data-id="${siswa.id}" data-name="${siswa.nama_siswa}" data-nis="${siswa.nis}" data-kelas="${siswa.kelas}">
                                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                            <span class="text-sm font-bold text-purple-600">${siswa.nama_siswa.charAt(0)}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">${siswa.nama_siswa}</p>
                                            <p class="text-sm text-gray-500">${siswa.nis} - ${siswa.kelas}</p>
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            html = `<p class="text-sm text-gray-500 text-center py-3">Siswa tidak ditemukan</p>`;
                        }
                        document.getElementById('siswaResults').innerHTML = html;

                        // Reattach click listeners
                        document.querySelectorAll('.siswa-item').forEach(item => {
                            item.addEventListener('click', function() {
                                const id = this.dataset.id;
                                const name = this.dataset.name;
                                const nis = this.dataset.nis;
                                const kelas = this.dataset.kelas;

                                document.getElementById('selectedSiswaId').value = id;
                                document.getElementById('searchSiswa').value = name + ' (' + nis + ' - ' + kelas + ')';
                                document.getElementById('searchSiswa').disabled = true;

                                document.querySelectorAll('.siswa-item').forEach(el => {
                                    el.classList.remove('border-purple-500', 'bg-purple-50');
                                });
                                this.classList.add('border-purple-500', 'bg-purple-50');

                                document.getElementById('siswaResults').innerHTML = `
                                    <div class="flex items-center gap-2 text-sm text-emerald-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Siswa dipilih: <strong>${name}</strong> (${nis} - ${kelas})</span>
                                        <button type="button" onclick="resetSiswaSearch()" class="text-rose-500 hover:text-rose-700 ml-2">Ubah</button>
                                    </div>
                                `;
                            });
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('siswaResults').innerHTML = `<p class="text-sm text-rose-500 text-center py-3">Gagal mencari siswa</p>`;
                    });
            }, 300);
        });
    </script>
</body>
</html>

