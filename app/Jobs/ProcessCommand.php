<?php

namespace App\Jobs;

use App\Runner\BackgroundJobService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCommand implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $command;

    /**
     * Create a new job instance.
     */
    public function __construct(array $command)
    {
        $this->command = $command;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Execute the command
        $className = $this->command['class'];
        $methodName = $this->command['method'];
        $params = $this->command['params'];

        try {
            $jobService = new BackgroundJobService();
            $jobService->runJobInBackground($className, $methodName, $params);
        } catch (\Exception $e) {
            // log to laravel log subsystem
            \Log::error("Job failed: {$e->getMessage()}");
        }
    }
}
