<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunImportLogEntries extends Command
{
    protected $signature = 'run:import-log-entries';
    protected $description = 'Run the import:log-entries command for a specific log file';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $currentDate = date('Y-m-d');
        // Call the import:log-entries command
        Artisan::call('import:log-entries', [
            'file' => 'storage/logs/laravel-'.$currentDate.'.log'
        ]);

        // Get the output of the command
        $output = Artisan::output();

        // Output the result
        $this->info('Log entries imported successfully.');
        $this->line($output);
    }
}