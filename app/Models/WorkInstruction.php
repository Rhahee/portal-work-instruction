<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkInstruction extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'excerpt', 'content_json', 'content_html', 'attachment_path', 'attachment_name', 'status', 'deletion_status', 'deletion_requested_at', 'deletion_requested_by', 'deletion_review_notes', 'deletion_reviewed_at', 'deletion_reviewed_by', 'published_at', 'review_notes', 'reviewed_at', 'reviewed_by', 'category_id', 'author_id'];
    protected function casts(): array { return ['content_json' => 'array', 'published_at' => 'datetime', 'reviewed_at' => 'datetime', 'deletion_requested_at' => 'datetime', 'deletion_reviewed_at' => 'datetime']; }

    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function author(): BelongsTo { return $this->belongsTo(User::class, 'author_id'); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewed_by'); }
    public function deletionRequester(): BelongsTo { return $this->belongsTo(User::class, 'deletion_requested_by'); }
    public function deletionReviewer(): BelongsTo { return $this->belongsTo(User::class, 'deletion_reviewed_by'); }

    public function scopePublished(Builder $query): Builder { return $query->where('status', 'published'); }
    public function scopeVisibleTo(Builder $query, ?User $user): Builder
    {
        if (! $user?->canReadIt()) {
            return $query->whereHas('category', fn (Builder $q) => $q->where('type', 'general'));
        }
        return $query;
    }
}
