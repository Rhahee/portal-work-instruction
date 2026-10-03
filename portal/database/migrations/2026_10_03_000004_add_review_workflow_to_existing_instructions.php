<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('work_instructions', function (Blueprint $table) {
            $table->string('status')->default('draft')->change();
            if (! Schema::hasColumn('work_instructions', 'review_notes')) $table->text('review_notes')->nullable()->after('published_at');
            if (! Schema::hasColumn('work_instructions', 'reviewed_at')) $table->timestamp('reviewed_at')->nullable()->after('review_notes');
            if (! Schema::hasColumn('work_instructions', 'reviewed_by')) $table->foreignId('reviewed_by')->nullable()->after('reviewed_at')->constrained('users')->nullOnDelete();
        });
    }
    public function down(): void { /* Keep review history if this migration is rolled back. */ }
};
