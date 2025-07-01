<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CleanOldLogs extends Command
{
    protected $signature = 'clean:old-logs';
    protected $description = 'Delete log files and database records older than 2 days';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Delete log files older than 2 days
        $logPath = storage_path('logs');
        $files = File::glob($logPath . '/*.log');

        foreach ($files as $file) {
            if (Carbon::now()->diffInDays(Carbon::createFromTimestamp(File::lastModified($file))) > 2) {
                File::delete($file);
                $this->info("Deleted log file: " . $file);
            }
        }

        // Delete database records older than 2 days
        DB::table('log_entries')->where('created_at', '<', Carbon::now()->subDays(2))->delete();
        $this->info("Deleted database records older than 2 days.");

        $this->info('Old logs cleaned successfully.');
    }
}