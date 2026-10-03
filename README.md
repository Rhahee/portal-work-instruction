# Work Instruction Portal

Portal pengetahuan internal untuk membuat, meninjau, menerbitkan, dan menemukan Work Instruction (WI). Aplikasi ini dibangun dengan Laravel + Blade dan didesain sebagai ruang dokumentasi yang responsif, sederhana, serta nyaman dibaca.

## Fitur v1.1

- Library publik untuk WI kategori umum, dengan pencarian isi/judul dan filter kategori.
- WI kategori IT hanya dapat dilihat oleh pengguna dengan role **IT** atau **Admin**.
- Autentikasi menggunakan NIK dan password dengan sesi Laravel.
- Dashboard IT untuk memantau draft, WI menunggu review, WI yang perlu direvisi, serta aktivitas artikel terbaru.
- Editor Tiptap untuk format teks, subjudul, list, tautan, gambar, highlight, inline code, dan blok Bash.
- Alur publikasi: pengguna IT menyimpan draft atau mengajukan review; Admin menerbitkan atau menolak dengan catatan.
- Alur penghapusan: pengguna IT mengajukan penghapusan; Admin menyetujui atau menolak. Persetujuan menghapus WI dan lampiran PDF privatnya.
- Lampiran PDF dapat ditambahkan pada WI; hanya IT/Admin yang dapat membukanya.
- Sidebar responsif: tetap pada layar desktop lebar dan berubah menjadi menu popup pada layar yang lebih sempit.

## Role dan akses

| Pengguna | Akses |
| --- | --- |
| Publik | Membaca WI umum yang sudah diterbitkan. |
| IT | Membaca WI umum dan IT, serta membuat, mengedit, mengajukan review, dan mengajukan penghapusan WI miliknya. |
| Admin | Mengelola seluruh WI, menyetujui/menolak review dan penghapusan, serta mengelola kategori dan pengguna. |

## Menjalankan lokal

Prasyarat: PHP, Composer, dan database SQLite atau MySQL.

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Buka `http://127.0.0.1:8000` di browser. Konfigurasi database dan panduan produksi tersedia di [SETUP.md](SETUP.md).

## Pengujian

```powershell
php artisan test
```

Test mencakup autentikasi, pembatasan WI IT, role, workflow review/penghapusan, lampiran PDF privat, highlight editor, serta filter kategori Library.

## Referensi desain

Arah visual mengambil inspirasi komposisi, whitespace, tipografi, dan interaksi dari [Gkizaenalzahse](https://www.gkizaenalzahse.my.id/) serta [Agence Vandenabeele](https://www.agencevandenabeele.be/nl?ref=siteinspire), tanpa menyalin desainnya.
