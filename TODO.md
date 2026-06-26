# TODO

- [x] Identifikasi proses hapus akun di `HomeController::deleteAccount()` (anonymize, bukan delete).
- [x] Verifikasi potensi penyebab rekap admin hilang: cek FK/cascade di tabel rekap.
- [x] Tambahkan migration untuk memastikan `formulir.user_id` tidak memakai cascade delete (gunakan `SET NULL`).
- [ ] Jalankan `php artisan migrate`.
- [ ] Uji manual: buat pesanan/pengembalian/denda/ulasan -> hapus akun -> pastikan halaman admin `data-tanggal`, `data-pesanan`, `data-pengembalian`, `data-denda`, `data-ulasan` tetap tampil.

