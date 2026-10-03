<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('work_instructions', function (Blueprint $table) {
            $table->string('deletion_status')->nullable()->after('status');
            $table->timestamp('deletion_requested_at')->nullable()->after('deletion_status');
            $table->foreignId('deletion_requested_by')->nullable()->after('deletion_requested_at')->constrained('users')->nullOnDelete();
            $table->text('deletion_review_notes')->nullable()->after('deletion_requested_by');
            $table->timestamp('deletion_reviewed_at')->nullable()->after('deletion_review_notes');
            $table->foreignId('deletion_reviewed_by')->nullable()->after('deletion_reviewed_at')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('work_instructions', function (Blueprint $table) {
            $table->dropForeign(['deletion_requested_by', 'deletion_reviewed_by']);
            $table->dropColumn(['deletion_status', 'deletion_requested_at', 'deletion_requested_by', 'deletion_review_notes', 'deletion_reviewed_at', 'deletion_reviewed_by']);
        });
    }
};
