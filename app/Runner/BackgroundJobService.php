<?php


namespace App\Runner;

use Symfony\Component\Process\Process;
use Exception;

class BackgroundJobService
{

    // pre approved classes
    protected $allowedClasses = [
        \App\Jobs\MyJobClass::class
    ];

    /**
     * Run a job in the background without Artisan.
     *
     * @param string $className
     * @param string $methodName
     * @param array $params
     * @return void
     * @throws Exception
     */
    public function runJobInBackground(string $className, string $methodName, array $params): void
    {
        // Validate the class name
        if (!$this->isAllowedClass($className)) {
            throw new Exception("Unauthorized class: $className. This class is not allowed to run.");
        }

        $phpScript = 'run_job.php';

        // Prepare the command to execute in the background
        $command = sprintf(
            'php %s %s %s %s',
            escapeshellarg($phpScript),
            escapeshellarg($className),
            escapeshellarg($methodName),
            implode(' ', array_map('escapeshellarg', $params))
        );

        // Determine background execution based on the OS
        if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
            // Windows OS
            $command = 'start /B ' . $command;
        } else {
            // Unix-based OS (Linux, macOS)
            $command .= ' > /dev/null 2>&1 &';
        }

        // Execute the command
        $process = Process::fromShellCommandline($command);


        try {
            $process->mustRun();
        } catch (\Throwable $e) {
            throw new Exception("Failed to run job in background: " . $e->getMessage());
        }
    }


    /**
     * Check if the given class is allowed.
     *
     * @param string $className
     * @return bool
     */
    private function isAllowedClass(string $className): bool
    {
        // Normalize the class name
        $fullyQualifiedClassName = strpos($className, '\\') === false
            ? 'App\\Jobs\\' . $className
            : $className;

        return in_array($fullyQualifiedClassName, $this->allowedClasses);
    }


}
