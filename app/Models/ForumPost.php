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
            $normalizedPath = ltrim($normalizedPath, '/');

            // "thumbnails/<uuid>.png" -> folder=thumbnails, filename=<uuid>.png
            $segments = explode('/', $normalizedPath, 2);

            if (count($segments) === 2 && $segments[0] !== '' && $segments[1] !== '') {
                // Served through a route (see ForumController::serveMedia()),
                // not linked to as a static file: the upload itself is written
                // under Laravel's own public_path(), but on some hosts the web
                // server's actual document root is a different directory (e.g.
                // a "public_html" folder that isn't Laravel's public/), so a
                // direct static link to it 404s even though the file is
                // genuinely on disk. Routing through PHP sidesteps that
                // mismatch — it only depends on Laravel routing working,
                // which it already does for every other page.
                //
                // absolute: false, same reasoning as below — host-agnostic.
                return route('forum.media', ['folder' => $segments[0], 'filename' => $segments[1]], false);
            }
        }

        // Root-relative on purpose, not asset(): asset() builds the URL from
        // APP_URL (or whatever host Laravel thinks it's on), which can end up
        // pointing at a different host/port than the one actually serving the
        // page (e.g. APP_URL=http://127.0.0.1:8000 while the site is really
        // browsed at a Herd/Valet *.test domain) — the thumbnail "uploads
        // fine" but the <img> src points at a host that isn't reachable, so
        // it renders broken. A path starting with "/" is resolved by the
        // browser against whatever host actually served the current page,
        // so it works regardless of what APP_URL is set to.
        return '/' . ltrim($normalizedPath, '/');
    }
}
