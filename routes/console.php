<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Sweeps every In Progress wish/donation and moves the ones whose 14-day
// fulfillment window has elapsed to Granted, same as the manual
// "Fulfilled" / "Complete Donation" buttons do. Hourly is more than
// enough granularity for a 14-day window while keeping the check cheap.
// NOTE: this only actually runs if the server has a single cron entry
// calling `php artisan schedule:run` every minute (standard Laravel
// scheduler setup) — see deployment notes.
Schedule::command('wishes:expire-in-progress')->hourly();
