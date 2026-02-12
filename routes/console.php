<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Process queued jobs every minute
Schedule::command('queue:work --stop-when-empty')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground()
    ->onFailure(function () {
        Log::error('Queue worker scheduled task failed');
    });

// Prune failed jobs older than 24 hours (daily at 2 AM)
Schedule::command('queue:prune-failed --hours=24')
    ->dailyAt('02:00')
    ->onFailure(function () {
        Log::error('Failed to prune failed jobs');
    });

// Prune completed job batches older than 7 days (daily at 2 AM)
Schedule::command('queue:prune-batches --hours=168')
    ->dailyAt('02:00')
    ->onFailure(function () {
        Log::error('Failed to prune job batches');
    });

// Prune expired sessions from database (daily at 3 AM)
Schedule::call(function () {
    $lifetime = config('session.lifetime', 120);
    $expired = now()->subMinutes($lifetime)->timestamp;
    
    DB::table('sessions')
        ->where('last_activity', '<', $expired)
        ->delete();
})->dailyAt('03:00')
    ->onFailure(function () {
        Log::error('Failed to prune expired sessions');
    });

// Clear expired cache entries (hourly)
Schedule::command('cache:prune-stale-tags')
    ->hourly()
    ->onFailure(function () {
        Log::error('Failed to prune stale cache tags');
    });
