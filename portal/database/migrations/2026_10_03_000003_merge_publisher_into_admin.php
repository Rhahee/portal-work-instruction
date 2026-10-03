<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void { DB::table('users')->where('role', 'publisher')->update(['role' => 'admin']); }
    public function down(): void { /* A merged Publisher account cannot be safely identified after migration. */ }
};
