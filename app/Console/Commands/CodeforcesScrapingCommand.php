<?php

namespace App\Console\Commands;

use App\Models\Handle;
use Illuminate\Console\Command;

class CodeforcesScrapingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'codeforces:scraping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Retrieve all student IDs and Codeforces handles
        $handles = Handle::select('id', 'cfhandle')->get();

        // Loop through the handles and execute codeforces.php with the parameters
        foreach ($handles as $handle) {
            $cfHandle = $handle->cfhandle;
            $studentId = $handle->id;
            
            $command = 'php "' . base_path('app\scraping\codeforces.php"') . ' ' . $cfHandle . ' ' . $studentId;
            exec('php "c:\Users\Jahirul Islam\Desktop\cph\cphub\app\scraping\codeforces.php"');
            // echo $command;

        }
    }
}
