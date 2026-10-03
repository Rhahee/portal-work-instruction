# DESIGN.md

## Design References

Referensi berikut digunakan sebagai inspirasi untuk komposisi, whitespace, tipografi, dan interaksi. Implementasi tidak menyalin desainnya secara langsung.

- [Gkizaenalzahse](https://www.gkizaenalzahse.my.id/)
- [Agence Vandenabeele](https://www.agencevandenabeele.be/nl?ref=siteinspire)

## 1. Design Overview

Work Instruction Portal adalah website internal perusahaan yang digunakan untuk membaca, mencari, membuat, dan mengelola Work Instruction (WI).

Website memiliki dua kelompok utama konten:

- **General WI** — Work Instruction yang dapat dibaca oleh pengguna non-IT.
- **IT WI** — Work Instruction yang ditujukan untuk pengguna IT.

Pengguna IT memiliki dashboard untuk membuat dan mengajukan Work Instruction miliknya. Admin memiliki dashboard untuk meninjau, memberikan catatan, menerbitkan, serta mengelola Work Instruction.

### Design Direction

Desain website menggunakan pendekatan:

**Paper-Styled · Simple · Tech**

Tampilan harus terasa seperti membaca dokumen atau lembar instruksi, tetapi tetap memiliki karakter aplikasi web modern.

Prioritas desain:

1. Readability
2. Simplicity
3. Clear Navigation
4. Content First
5. Consistency
6. Minimal Visual Noise

---

# 2. Design Principles

## 2.1 Content First

Work Instruction adalah elemen terpenting.

UI tidak boleh mengalahkan konten.

Hindari:

- dekorasi berlebihan
- animasi berlebihan
- terlalu banyak warna
- terlalu banyak card
- layout yang terlalu padat

Gunakan whitespace untuk memisahkan informasi.

---

## 2.2 Paper-Styled

Halaman Work Instruction harus memiliki kesan seperti membaca dokumen.

Karakteristik utama:

- background halaman sedikit berbeda dari area dokumen
- area artikel menyerupai lembar kertas
- border tipis
- shadow sangat lembut
- typography nyaman untuk membaca teks panjang
- struktur heading yang jelas

Contoh konsep:

```text
Background
│
└── Paper Container
    │
    ├── Category
    ├── WI Title
    ├── Metadata
    ├── Divider
    │
    └── Article Content
```

---

## 2.3 Simple

Setiap halaman harus mempunyai satu tujuan utama.

Contoh:

```text
Home
→ mencari WI

WI Detail
→ membaca WI

Dashboard
→ mengelola WI

Editor
→ membuat atau mengubah WI
```

Jangan memberikan terlalu banyak action dengan tingkat visual yang sama.

---

## 2.4 Tech

Nuansa teknologi diberikan melalui:

- typography
- iconography
- grid
- metadata
- label
- numbering
- subtle technical details

Bukan melalui penggunaan efek futuristik berlebihan.

Hindari tampilan:

- cyberpunk
- neon
- glowing UI
- glassmorphism berlebihan

---

# 3. Visual Hierarchy

Urutan perhatian pengguna:

```text
Page Title
    ↓
Primary Information
    ↓
Primary Action
    ↓
Content
    ↓
Metadata
    ↓
Secondary Action
```

Judul dan isi WI harus selalu lebih dominan daripada elemen dekoratif.

---

# 4. Color System

Gunakan sistem warna yang sederhana.

## Base

```text
Background
Paper
Primary Text
Secondary Text
Border
Muted Background
```

Mayoritas interface menggunakan warna netral.

Contoh karakter:

```text
Background      → warm / soft neutral
Paper           → white / near-white
Primary Text    → near-black
Secondary Text  → gray
Border          → light gray
```

## Accent

Gunakan satu warna accent utama untuk:

- link
- active navigation
- primary button
- focus state
- selected item

## Semantic Colors

Warna tambahan hanya digunakan untuk status.

```text
Success
Warning
Error
Information
```

Jangan menggunakan semantic color sebagai dekorasi.

---

# 5. Typography

Typography harus mendukung pembacaan Work Instruction dalam waktu lama.

Gunakan maksimal dua keluarga font:

```text
Primary Font
→ UI dan body text

Secondary / Mono Font
→ metadata, code, technical information
```

## Hierarchy

```text
Display
H1
H2
H3
H4
Body
Small
Caption
Metadata
```

### Article

Konten WI harus mempunyai:

- line-height yang cukup longgar
- paragraph spacing yang konsisten
- panjang baris yang nyaman
- heading yang mudah dibedakan
- list yang jelas

Hindari body text terlalu kecil.

---

# 6. Spacing System

Gunakan spacing berbasis sistem konsisten.

Contoh:

```text
4
8
12
16
24
32
48
64
```

Gunakan spacing kecil untuk hubungan elemen yang dekat.

Gunakan spacing besar untuk memisahkan section.

---

# 7. Border & Radius

Paper-styled UI sebaiknya tidak menggunakan radius terlalu besar.

Gunakan:

```text
Small Radius
Medium Radius
```

Untuk:

- button
- input
- dropdown
- small card
- modal

Paper/article container dapat menggunakan radius minimal atau hampir square.

Border harus tipis dan subtle.

---

# 8. Shadows

Shadow hanya digunakan untuk menunjukkan elevation.

Contoh penggunaan:

- paper/article container
- dropdown
- modal
- floating toolbar

Hindari shadow tebal.

---

# 9. Icons

Gunakan icon sederhana dengan gaya konsisten.

Icon digunakan untuk membantu mengenali action seperti:

```text
Search
Edit
Delete
Publish
Image
Link
Table
Undo
Redo
Menu
User
Settings
```

Jangan menggunakan icon tanpa label apabila fungsi icon berpotensi ambigu.

---

# 10. Main Website Structure

Struktur utama website:

```text
Website
│
├── Home
│
├── WI Library
│   ├── General WI
│   └── IT WI
│
├── WI Detail
│
└── IT / Admin
    │
    ├── Login
    │
    └── Dashboard
        ├── WI Submission & Review
        ├── WI Editor
        ├── Category Management (Admin)
        └── User Management (Admin)
```

---

# 11. Public / Reader Layout

Reader interface harus sederhana dan berorientasi pada pencarian informasi.

Struktur dasar:

```text
Header
│
├── Logo / Website Name
├── Navigation
└── Search

Main Content
│
├── Page Heading
├── Search / Filter
└── WI Content

Footer
```

Navigation tidak boleh terlalu kompleks.

---

# 12. Home Page

Home menjadi titik awal pengguna mencari Work Instruction.

Struktur yang disarankan:

```text
Header

Hero / Introduction
├── Website Title
├── Short Description
└── Search WI

WI Categories
├── General WI
└── IT WI

Recent / Available WI

Footer
```

Search harus menjadi salah satu elemen paling mudah ditemukan.

---

# 13. WI Library

Library digunakan untuk menjelajah Work Instruction.

Layout:

```text
Page Header
├── Title
└── Description

Toolbar
├── Search
└── Filter

WI List
├── WI Item
├── WI Item
├── WI Item
└── ...
```

Setiap WI item minimal menampilkan:

```text
Category
Title
Short Description / Excerpt
Metadata
```

Hindari card dengan dekorasi berlebihan.

Gunakan divider atau border sederhana untuk memisahkan item.

---

# 14. WI Detail Page

Ini adalah halaman terpenting untuk Reader.

Gunakan konsep:

**Digital Paper Document**

Layout desktop:

```text
┌─────────────────────────────────────────────┐
│ Header                                      │
├─────────────────────────────────────────────┤
│                                             │
│          ┌───────────────────────┐          │
│          │ CATEGORY              │          │
│          │                       │          │
│          │ Work Instruction      │          │
│          │ Title                 │          │
│          │                       │          │
│          │ Metadata              │          │
│          │ ───────────────────   │          │
│          │                       │          │
│          │ Article Content       │          │
│          │                       │          │
│          │                       │          │
│          └───────────────────────┘          │
│                                             │
└─────────────────────────────────────────────┘
```

Article width jangan memenuhi seluruh layar.

Tujuannya adalah mempertahankan kenyamanan membaca.

---

# 15. Article Content

Konten WI dapat berisi:

```text
Heading
Paragraph
Bold
Italic
Underline
Strikethrough
Inline Code
Highlight

Bullet List
Numbered List

Link
Image
Table
Divider
Bash Code Block
PDF Attachment (visible to IT/Admin only)
```

Semua elemen harus mempunyai styling yang konsisten ketika:

```text
Editor
        ↓
Database
        ↓
Reader
```

Tampilan di editor dan hasil publikasi sebisa mungkin mendekati satu sama lain.

---

# 16. Login Page

Login IT/Admin menggunakan:

**NIK — Nomor Induk Karyawan**

Login page harus sangat sederhana.

Layout:

```text
┌──────────────────────────────┐
│                              │
│        Website Identity      │
│                              │
│          IT / Admin          │
│                              │
│      NIK                     │
│      [______________]        │
│                              │
│      [     Login     ]       │
│                              │
└──────────────────────────────┘
```

Fokus utama hanya pada proses autentikasi.

Hindari:

- sidebar
- informasi tidak relevan
- dekorasi besar
- form terlalu kompleks

---

# 17. Dashboard

Dashboard digunakan oleh IT untuk membuat dan mengajukan WI, serta oleh Admin untuk meninjau dan menerbitkannya.

Gunakan struktur:

```text
┌──────────────┬──────────────────────────────┐
│              │ Topbar                       │
│ Sidebar      ├──────────────────────────────┤
│              │                              │
│ Dashboard    │ Main Content                 │
│ WI           │                              │
│ Categories   │                              │
│ Users        │                              │
│              │                              │
└──────────────┴────────────────────────────
