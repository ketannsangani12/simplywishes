<?php

namespace App\Console\Commands;

use App\Models\Donation;
use App\Models\Wish;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * A granted wish / accepted donation is only ever meant to sit "In
 * Progress" for 14 days — the grantor (for a wish) or donor (for a
 * donation) is expected to mark it Fulfilled / Complete within that
 * window. This is exactly what the disclaimer shown at grant/accept
 * time (and the confirmation emails) promises: "After 14 days, it will
 * be marked as Fulfilled/Granted" — automatically, without anyone
 * having to click anything.
 *
 * Previously nothing actually enforced that promise, so a post could
 * stay stuck In Progress indefinitely if the responsible person never
 * came back to finish the manual step. This command finds every wish
 * still In Progress (wish_progress_status = 1) and every donation
 * still In Progress (status = 2) whose 14-day window has elapsed, and
 * moves them to Granted the same way the manual "Fulfilled" / "Complete
 * Donation" buttons do.
 */
class ExpireInProgressWishesAndDonations extends Command
{
    protected $signature = 'wishes:expire-in-progress';

    protected $description = 'Automatically move wishes/donations from In Progress to Granted once their 14-day fulfillment window has elapsed.';

    private const FULFILLMENT_WINDOW_DAYS = 14;

    public function handle(): int
    {
        $cutoff = Carbon::now()->subDays(self::FULFILLMENT_WINDOW_DAYS);

        $wishesMoved = $this->expireWishes($cutoff);
        $donationsMoved = $this->expireDonations($cutoff);

        $this->info("Auto-granted {$wishesMoved} wish(es) and {$donationsMoved} donation(s) past the 14-day fulfillment window.");

        return self::SUCCESS;
    }

    /**
     * wish_progress_status: 0 = Current, 1 = In Progress (granted_date set
     * when a grantor accepted it), 2 = Granted (what the manual "Fulfilled"
     * button on wish-preview.blade.php sets). granted_date is stored as a
     * plain 'Y-m-d H:i:s' string, not a real datetime column, so the cutoff
     * is formatted the same way for a correct lexicographic comparison.
     */
    private function expireWishes(Carbon $cutoff): int
    {
        // withoutGlobalScopes(): this is a console command with no
        // authenticated user, so ExcludeBlockedUsersScope already no-ops —
        // but being explicit here means this sweep can never silently skip
        // a wish just because its owner happens to be in a blocked pair.
        $query = Wish::withoutGlobalScopes()
            ->where('wish_status', 1)
            ->where('wish_progress_status', 1)
            ->whereNotNull('granted_date')
            ->where('granted_date', '<=', $cutoff->format('Y-m-d H:i:s'));

        $ids = $query->pluck('w_id');

        if ($ids->isEmpty()) {
            return 0;
        }

        Wish::withoutGlobalScopes()
            ->whereIn('w_id', $ids)
            ->update([
                'fulfilled_date' => now(),
                'wish_progress_status' => 2,
                'date_updated' => now(),
            ]);

        Log::info('Auto-granted wishes past the 14-day fulfillment window.', ['wish_ids' => $ids->all()]);

        return $ids->count();
    }

    /**
     * status: 0 = draft, 1 = Current, 2 = In Progress (accepted_at set when
     * a donor accepted it), 3 = Granted/Completed (what the manual
     * "Complete Donation" button on donation-preview.blade.php sets).
     */
    private function expireDonations(Carbon $cutoff): int
    {
        $query = Donation::withoutGlobalScopes()
            ->where('status', 2)
            ->whereNotNull('accepted_at')
            ->where('accepted_at', '<=', $cutoff->format('Y-m-d H:i:s'));

        $ids = $query->pluck('id');

        if ($ids->isEmpty()) {
            return 0;
        }

        Donation::withoutGlobalScopes()
            ->whereIn('id', $ids)
            ->update([
                'completed_at' => now(),
                'status' => 3,
                'process_status' => 2,
                'date_updated' => now(),
            ]);

        Log::info('Auto-completed donations past the 14-day fulfillment window.', ['donation_ids' => $ids->all()]);

        return $ids->count();
    }
}
