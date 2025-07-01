<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LogEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ImportLogEntries extends Command
{
    protected $signature = 'import:log-entries {file}';
    protected $description = 'Import log entries from a log file into the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $file = $this->argument('file');

        // Debugging output
        $this->info("Checking file: $file");

        if (!file_exists($file)) {
            $this->error("File not found: $file");
            return;
        }

        // Read the entire file into an array of lines
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines === false) {
            $this->error("Failed to read file: $file");
            return;
        }

        $remainingLines = [];

        foreach ($lines as $line) {
            // Parse the log line
            if (strpos($line, 'Curl Request') !== false || strpos($line, 'Curl Response') !== false) {
                preg_match('/\[(.*?)\] local.INFO: (Curl Request|Curl Response): (.*)/', $line, $matches);
                if (count($matches) === 4) {
                    $timestamp = Carbon::parse($matches[1]);
                    $type = $matches[2];
                    $message = $matches[3];

                    $authId = Session::get('auth_id') ? Session::get('auth_id') : '';
                    $authName = '';
                    if ($authId != '') {
                        $authName = DB::table('tbl_stf_auth_prsn')
                                    ->where('stf_auth_id', $authId)
                                    ->value('stf_auth_name');
                    }

                    // Check if it's a Curl Response with error true
                    if ($type === 'Curl Response') {
                        $response = json_decode($message, true);
                        if (isset($response['response']) && strpos($response['response'], '"error":true') !== false) {
                            // Insert into the database
                            LogEntry::create([
                                'created_at' => $timestamp,
                                'type' => $type,
                                'message' => $message,
                                'request_by' => $authName
                            ]);
                        }
                    } else {
                        // Insert into the database
                        LogEntry::create([
                            'created_at' => $timestamp,
                            'type' => $type,
                            'message' => $message,
                            'request_by' => $authName
                        ]);
                    }
                } else {
                    // Add the line back to the remaining lines
                    $remainingLines[] = $line;
                }
            } else {
                // Add the line back to the remaining lines
                $remainingLines[] = $line;
            }
        }

        // Write the remaining lines back to the file
        file_put_contents($file, implode(PHP_EOL, $remainingLines) . PHP_EOL);

        $this->info('Log entries imported successfully.'. $authName);
    }
}