<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void { Schema::create('work_instructions', function (Blueprint $table) { $table->id(); $table->string('title'); $table->string('slug')->unique(); $table->string('excerpt', 500)->nullable(); $table->json('content_json')->nullable(); $table->longText('content_html')->nullable(); $table->string('status')->default('draft'); $table->timestamp('published_at')->nullable(); $table->text('review_notes')->nullable(); $table->timestamp('reviewed_at')->nullable(); $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete(); $table->foreignId('category_id')->constrained()->restrictOnDelete(); $table->foreignId('author_id')->constrained('users')->restrictOnDelete(); $table->timestamps(); $table->index(['status', 'published_at']); }); }
    public function down(): void { Schema::dropIfExists('work_instructions'); }
};
