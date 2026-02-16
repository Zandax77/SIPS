# Plan: Add Camera Capture for Violation Attachments

## Information Gathered:
- **Location**: Halaman "Catat Pelanggaran" di `resources/views/catat-pelanggaran.blade.php`
- **Current Feature**: File upload untuk lampiran (foto/dokumen) dengan validasi max 1MB
- **Target**: Tambah fitur ambil foto langsung dengan kamera untuk pelanggaran Sedang/Berat

## Implementation Steps:

### Step 1: Update View - catat-pelanggaran.blade.php
- [x] 1.1 Add camera capture button next to file upload
- [x] 1.2 Add hidden video element for camera stream
- [x] 1.3 Add canvas element for capturing and compressing image
- [x] 1.4 Add modal for camera interface
- [x] 1.5 Add JavaScript functions:
  - [x] openCameraModal() - Open camera modal and request access
  - [x] closeCameraModal() - Close modal and stop camera
  - [x] startCamera() - Access device camera
  - [x] stopCamera() - Stop camera stream
  - [x] capturePhoto() - Capture frame from video
  - [x] compressImage() - Compress image to under 1MB
  - [x] retakePhoto() - Clear captured photo and retake
  - [x] createCameraInput() - Create hidden file input for form
  - [x] removeCameraInput() - Remove hidden input
- [x] 1.6 Add preview of captured photo in the form
- [x] 1.7 Integrate with existing form submission

### Step 2: Testing
- [ ] 2.1 Test camera access on mobile device
- [ ] 2.2 Test photo capture
- [ ] 2.3 Verify compression works (photo under 1MB)
- [ ] 2.4 Test form submission with captured photo
- [ ] 2.5 Verify stored file size

## Technical Details:

### Compression Algorithm:
- Use Canvas API to compress JPEG
- Start with quality 0.8, progressively reduce until under 1MB
- Maximum dimension: 1920px (to balance quality and size)

### Fallback:
- If camera not available, show file upload option
- Support both camera capture and file upload

## Expected Files to Edit:
1. `resources/views/catat-pelanggaran.blade.php` - Main implementation

## ✅ IMPLEMENTATION COMPLETE

