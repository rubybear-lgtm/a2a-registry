<?php

use App\Console\Commands\RefreshAgentListings;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(RefreshAgentListings::class, ['--limit' => 25])
    ->everyMinute()
    ->withoutOverlapping();
