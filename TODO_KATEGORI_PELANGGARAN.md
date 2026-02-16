# TODO - Perubahan Validasi Kategori Pelanggaran

## Task: Mengubah pengisian kategori pelanggaran agar dapat memasukkan nama kategori yang sama dengan poin berbeda

### Steps:
- [x] 1. Modify controller validation in `KendaliJenisPelanggaran.php`
- [x] 2. Create migration to add unique constraint on (nama, poin) combination
- [x] 3. Run migration to apply database constraint
- [x] 4. Update view to show points in category dropdown
- [x] 5. Update filter to use category ID instead of name

### Details:
- **Current behavior**: Category name must be unique
- **Desired behavior**: Allow same category name if the points are different

