<?php

require_once '../../vendor/autoload.php';

use App\Runner\BackgroundJobService;

try {
    // Retrieve arguments from the command line
    if ($argc < 3) {
        throw new Exception("Usage: php JobRunner.php <className> <methodName> [params...]");
    }

    $className = $argv[1];
    $methodName = $argv[2];
    $params = array_slice($argv, 3);

    // Run the job in the background
    $jobService = new BackgroundJobService();
    $result = $jobService->runJobInBackground($className, $methodName, $params);

    // Log and output success
    $message = sprintf("Job '%s->%s' executed.", $className, $methodName);
    echo $message . PHP_EOL;
} catch (Throwable $e) {
    // Log and output error
    $errorMessage = sprintf("Job execution failed: %s", $e->getMessage());
    echo $errorMessage . PHP_EOL;
    exit(1);
}

