<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('app:sync-popular-anime')->dailyAt('03:00');
Schedule::command('app:sync-popular-chinese-anime')->dailyAt('03:30');
Schedule::command('app:sync-seasonal-anime')->dailyAt('04:00');
Schedule::command('app:sync-top-rated-anime')->dailyAt('04:30');
Schedule::command('app:sync-trending-anime')->dailyAt('05:00');
Schedule::command('app:sync-trending-chinese-anime')->dailyAt('05:30');