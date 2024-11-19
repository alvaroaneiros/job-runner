<?php

namespace App\Console\Commands;

use App\Jobs\ProcessCommand;
use Bus;
use Illuminate\Console\Command;
use App\Runner\BackgroundJobService;
use Exception;

class JobRunner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job:run {className} {methodName} {params?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a specified job method with optional parameters';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            // Retrieve arguments from the command
            $className = $this->argument('className');
            $methodName = $this->argument('methodName');
            $params = $this->argument('params') ?? [];

            // $command = [
            //     'class' => $className,
            //     'method' => $methodName,
            //     'params' => $params,
            // ];
            
            // Dispatch the job to the queue
            // Bus::dispatch(new ProcessCommand($command));

            // Run the job in the background
            $jobService = new BackgroundJobService();
            $result = $jobService->runJobInBackground($className, $methodName, $params);

            // Log and output success
            $this->info(sprintf("Job '%s->%s' executed successfully.", $className, $methodName));
        } catch (Exception $e) {
            // Log and output error
            $this->error(sprintf("Job execution failed: %s", $e->getMessage()));
            return 1; // Return an error status code
        }

        return 0; // Return success status code
    }
}
