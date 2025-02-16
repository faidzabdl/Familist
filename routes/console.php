<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

// Command inspire yang sudah ada
Artisan::command('inspire', function () {
    $this->comment(\Illuminate\Foundation\Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Tambahkan jadwal untuk reminder:send
app()->booted(function () {
    $schedule = app(Schedule::class);
    $schedule->command('penginggat:send')
    ->everyMinute()
    ->appendOutputTo(storage_path('logs/scheduler.log')); // Log semua output
});
