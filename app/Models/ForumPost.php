<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use Illuminate\Support\Str;

class ForumPost extends Model
{
    protected $table = 'forum';
    protected $primaryKey = 'e_id';

    protected $fillable = [
        'e_title',
        'e_text',
        'description',
        'e_image',
        'article_image',
        'featured_video_url',
        'status',
        'is_video_only',
        'created_at',
        'created_by',
        'updated_by',
        'updated_at',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ForumComment::class, 'forum_id', 'e_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(ForumLike::class, 'forum_id', 'e_id');
    }

    public function imageUrl(): ?string
    {
        return $this->resolveMediaUrl($this->e_image ?: $this->article_image);
    }

    public function videoUrl(): ?string
    {
        return $this->resolveMediaUrl($this->featured_video_url);
    }

    private function resolveMediaUrl(?string $path): ?string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return null;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        $normalizedPath = str_replace('\\', '/', $path);

        foreach (['/public_html/', '/public/'] as $segment) {
            if (str_contains($normalizedPath, $segment)) {
                $normalizedPath = Str::after($normalizedPath, $segment);
                break;
            }
        }

        $normalizedPath = ltrim($normalizedPath, '/');

        if (str_starts_with($normalizedPath, 'public/')) {
            $normalizedPath = Str::after($normalizedPath, 'public/');
        }

        if (str_contains($normalizedPath, 'uploads/forum/')) {
            $normalizedPath = Str::after($normalizedPath, 'uploads/forum/');
            $normalizedPath = 'uploads/forum/' . ltrim($normalizedPath, '/');
        }

        return asset($normalizedPath);
    }
}
