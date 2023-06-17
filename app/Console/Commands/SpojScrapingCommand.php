<?php

namespace App\Console\Commands;

use App\Models\Handle;
use Illuminate\Console\Command;

class SpojScrapingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spoj:scraping';

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
        $handles = Handle::select('id', 'spojhandle', 'spoj_last_submission')->get();

        // Loop through the handles and execute codeforces.php with the parameters
        foreach ($handles as $handle) {
            $spojHandle = $handle->spojhandle;
            $studentId = $handle->id;
            $spojLast = $handle->spoj_last_submission;


            $command = 'php "' . base_path('app\scraping\spoj.php') . '" "' . $spojHandle . '" "' . $studentId . '" "' . $spojLast . '"';

            // Execute the command and capture the output
            shell_exec($command);

            // Print the output
            echo $command;
        }
    }
}
