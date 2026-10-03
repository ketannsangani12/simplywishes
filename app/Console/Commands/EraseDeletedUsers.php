<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\AccountEraser;
use Illuminate\Console\Command;

/**
 * One-time cleanup for accounts deleted under the old "Delete my account"
 * behaviour, which only renamed the user to "Deleted User" and set
 * users.deleted_at, leaving their posts, comments, friendships, requests,
 * blocks and chats on the site. Those accounts still showed up in friends
 * lists, search and the inbox, and their wishes/donations stayed open.
 *
 * Runs the same full erasure as deleting an account today
 * (App\Services\AccountEraser) for every user with deleted_at set.
 */
class EraseDeletedUsers extends Command
{
    protected $signature = 'users:erase-deleted {--force : Skip the confirmation prompt}';

    protected $description = 'Permanently erase all data of accounts that were deleted under the old "Deleted User" behaviour.';

    public function handle(AccountEraser $eraser): int
    {
        $userIds = User::withoutGlobalScopes()->whereNotNull('deleted_at')->pluck('id');

        if ($userIds->isEmpty()) {
            $this->info('No previously deleted accounts left to erase.');

            return self::SUCCESS;
        }

        $this->warn("{$userIds->count()} previously deleted account(s) will be permanently erased, with all their posts, comments, friendships and chats. This cannot be undone.");

        if (! $this->option('force') && ! $this->confirm('Continue?')) {
            $this->info('Nothing was changed.');

            return self::SUCCESS;
        }

        foreach ($userIds as $userId) {
            $eraser->erase((int) $userId);
        }

        $this->info("Erased {$userIds->count()} account(s).");

        return self::SUCCESS;
    }
}
