<?php

namespace App\Models\Scopes;

use App\Models\FriendBlock;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

/**
 * Blocking on this site is mutual and total: once two users have blocked
 * each other (in either direction), neither one is supposed to see any of
 * the other's posts anywhere on the site — wishes, donations, happy
 * stories, forum articles/videos, all of it. Rather than remembering to
 * add a "not blocked" filter to every listing/search/detail query across
 * every controller (and inevitably missing one), this scope is attached
 * once to each of those models' `booted()` and quietly excludes
 * blocked-pair content from every query automatically, including a direct
 * `find()`/`show()` lookup by id (so a direct link to a blocked-pair post
 * 404s the same way a nonexistent one would).
 *
 * It only ever applies to a real logged-in web request (Auth::check()) —
 * console commands, queued jobs, and the admin panel (which has its own
 * moderation reasons to see everything) never touch this at all.
 */
class ExcludeBlockedUsersScope implements Scope
{
    public function __construct(private readonly string $ownerColumn)
    {
    }

    public function apply(Builder $builder, Model $model): void
    {
        $userId = Auth::id();

        if (! $userId) {
            return;
        }

        // The admin panel shares the same login/user table as the public
        // site, but moderators need to see every post regardless of any
        // personal blocks their own account happens to be part of.
        if (request()?->routeIs('admin.*')) {
            return;
        }

        // Looked up once per request (every comment/post query on a page
        // reuses it). Kept on the request, not in a static, so a long-lived
        // process (queue worker, Octane, tests) never reuses a stale list.
        $attributes = request()->attributes;
        $cacheKey = 'blocked_user_ids.' . $userId;
        if (! $attributes->has($cacheKey)) {
            $attributes->set($cacheKey, FriendBlock::blockedUserIdsFor((int) $userId)->all());
        }

        $blockedIds = $attributes->get($cacheKey);

        if ($blockedIds === []) {
            return;
        }

        $builder->whereNotIn($model->qualifyColumn($this->ownerColumn), $blockedIds);
    }
}
