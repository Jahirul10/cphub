<?php

namespace App\Console\Commands;

use App\Models\Handle;
use Illuminate\Console\Command;

class VjudgeScrapingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vjudge:scraping';

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
        $handles = Handle::select('id', 'vjhandle', 'vj_last_submission')->get();

        // Loop through the handles and execute codeforces.php with the parameters
        foreach ($handles as $handle) {
            $vjHandle = $handle->vjhandle;
            $studentId = $handle->id;
            $vjLast=$handle->vj_last_submission;
            
            
            $command = 'php "' . base_path('app/scraping/vjudge.php') . '" "' . $vjHandle . '" "' . $studentId . '" "' . $vjLast . '"';
            
            // Execute the command and capture the output
            shell_exec($command);
            
            // Print the output
            // echo $command;
    }
}
}