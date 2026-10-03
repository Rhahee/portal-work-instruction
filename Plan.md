# Work Instruction Portal

## 1. Project Overview
Website internal sederhana untuk membaca dan mengelola
Work Instruction perusahaan.

## 2. Objectives
- Mempermudah pencarian Work Instruction
- Menyediakan WI umum dan WI khusus IT
- Membuat proses publikasi WI sederhana
- Menyediakan dashboard Admin/Penerbit

## 3. Technology
- PHP
- MySQL
- HTML
- CSS
- JavaScript

## 4. User Roles

### Pembaca Umum
- Tidak memerlukan akun
- Membaca dan mencari WI umum

### IT
- Login menggunakan NIK
- Membaca WI umum dan WI khusus IT
- Membuat dan merevisi WI miliknya
- Mengajukan WI untuk review Admin

### Admin
- Membaca seluruh WI
- Meninjau, memberi catatan, menyetujui, menolak, dan menerbitkan WI
- Mengelola user dan kategori

## 5. Core Features

### WI Library
- Daftar WI
- Category
- Search
- Filter
- Detail WI

### Authentication
- Login menggunakan NIK
- Role-based access

### Dashboard
- Overview
- WI Management
- Category Management
- User Management

### Review Workflow
- Draft
- Pending review
- Published
- Rejected dengan catatan Admin, lalu dapat direvisi dan diajukan kembali oleh IT

### WI Editor
- Undo / Redo
- Heading / Paragraph
- Bold / Italic / Underline
- Inline Code
- Highlight
- Bullet / Numbered List
- Indentation
- Link
- Image
- Table
- Divider
- Bash Code Block
- Lampiran PDF privat untuk semua WI; hanya dapat dilihat role IT/Admin

## 6. Development Phases

Phase 1 — Project Structure
Phase 2 — Database
Phase 3 — Authentication
Phase 4 — Public WI Library
Phase 5 — Dashboard
Phase 6 — WI Editor
Phase 7 — Search & Filtering
Phase 8 — UI Polish
Phase 9 — Security & Testing
