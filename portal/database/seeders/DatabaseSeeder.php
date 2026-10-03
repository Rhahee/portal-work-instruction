<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use App\Models\WorkInstruction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create(['name' => 'Portal Administrator', 'nik' => 'ADMIN001', 'role' => 'admin', 'password' => Hash::make('password')]);
        $general = Category::create(['name' => 'Operasional Umum', 'slug' => 'operasional-umum', 'type' => 'general', 'description' => 'Panduan operasional untuk seluruh karyawan.']);
        $it = Category::create(['name' => 'Infrastruktur IT', 'slug' => 'infrastruktur-it', 'type' => 'it', 'description' => 'Panduan untuk tim IT.']);
        WorkInstruction::create(['title' => 'Pengajuan Perangkat Kerja', 'slug' => 'pengajuan-perangkat-kerja', 'excerpt' => 'Langkah pengajuan perangkat kerja untuk karyawan.', 'content_json' => ['type' => 'doc', 'content' => []], 'content_html' => '<h2>Tujuan</h2><p>Gunakan panduan ini untuk mengajukan perangkat kerja.</p><ol><li>Isi formulir pengajuan.</li><li>Dapatkan persetujuan atasan.</li><li>Kirim ke tim terkait.</li></ol>', 'status' => 'published', 'published_at' => now(), 'category_id' => $general->id, 'author_id' => $admin->id]);
        WorkInstruction::create(['title' => 'Akses VPN Perusahaan', 'slug' => 'akses-vpn-perusahaan', 'excerpt' => 'Prosedur akses VPN untuk tim IT.', 'content_json' => ['type' => 'doc', 'content' => []], 'content_html' => '<h2>Akses terbatas</h2><p>Dokumen ini khusus pengguna dengan role IT.</p>', 'status' => 'published', 'published_at' => now(), 'category_id' => $it->id, 'author_id' => $admin->id]);
    }
}
