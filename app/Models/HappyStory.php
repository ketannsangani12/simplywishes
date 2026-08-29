<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Wish;

class HappyStory extends Model
{
    protected $table = 'happy_stories';
    protected $primaryKey = 'hs_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'wish_id',
        'story_text',
        'story_image',
        'status',
        'created_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function wish(): BelongsTo
    {
        return $this->belongsTo(Wish::class, 'wish_id', 'w_id');
    }

    /**
     * URL for this story's image, however it was set: an uploaded file, one
     * of the bundled default picks, or (legacy) a full external URL.
     *
     * Routed through SiteController::happyStoryUpload() /
     * defaultHappyStoryImage() rather than linked to directly as a static
     * file — see the doc comments there for why.
     */
    public function imageUrl(): ?string
    {
        $path = trim((string) $this->story_image);

        if ($path === '') {
            return null;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'uploads/happy-stories/')) {
            return route('happy.stories.upload', ['filename' => basename($path)], false);
        }

        if (str_starts_with($path, 'images/happy-stories-default/')) {
            return route('happy.stories.default-image', ['filename' => basename($path)], false);
        }

        return '/' . $path;
    }
}
