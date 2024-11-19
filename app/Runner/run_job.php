<?php

use App\Logger\SimpleLogger;
use App\Runner\JobDatabaseManager;

require_once __DIR__ . '../../../vendor/autoload.php';

$simpleLogger = new SimpleLogger();
$dbManager = new JobDatabaseManager();
try {
    
    // Retrieve arguments from the command line
    if ($argc < 3) {
        throw new Exception("Invalid arguments. Usage: php run_job.php <class> <method> [params...]");
    }

    $className = $argv[1];
    $methodName = $argv[2];
    $params = array_slice($argv, 3);

    $logEntry = [
        'timestamp' => now(),
        'class' => $className,
        'method' => $methodName,
        'params' => json_encode($params),
        'status' => 'pending',
    ];
    // Add a job to DB
    $jobId = $dbManager->addJob($className, $methodName, $params);
    // Update job status
    $dbManager->updateJobStatus($jobId, 'running', null);
    // Execute the job

    $result = runJob($className, $methodName, $params);

    // Log success
    $logEntry['status'] = 'success';
    $logEntry['output'] = is_string($result) ? $result : json_encode($result);
    
    $dbManager->completeJobStatus($jobId, $result);

    $simpleLogger->setOutputFile('jobs_run.log');
    $simpleLogger->notice("Job '$className->$methodName' executed successfully.", $logEntry);

    exit(0);
} catch (Throwable $e) {

    // Log failure
    $logEntry['status'] = 'failure';
    $logEntry['output'] = $e->getMessage();

    $simpleLogger->setOutputFile('background_jobs_errors.log');
    
    if ($dbManager->canRetry($jobId)) {
        $dbManager->incrementRetryCount($jobId);
        $dbManager->updateJobStatus($jobId, 'retrying', $e->getMessage());
        $simpleLogger->warning("Job '$className->$methodName' failed. Retrying...");
    } else {
        $dbManager->updateJobStatus($jobId, 'failed', $e->getMessage());
        $simpleLogger->error("Job execution failed: {$e->getMessage()}", $logEntry);
    }

    exit(1);
}


/**
 * Run a job directly in the current process.
 *
 * @param string $className
 * @param string $methodName
 * @param array $params
 * @return mixed
 * @throws Exception
 */
function runJob(string $className, string $methodName, array $params): mixed
{
    $classFilename = "../../app/Jobs/{$className}.php";

    // Step 1: Check if file exists
    if (!file_exists($classFilename)) {
        throw new Exception("File not found: {$classFilename}");
    }

    // Step 2: Include the file
    require_once $classFilename;

    $fullyQualifiedClassName = "\\App\\Jobs\\{$className}";

    // Step 3: Check if class exists
    if (!class_exists($fullyQualifiedClassName)) {
        throw new Exception("Class {$fullyQualifiedClassName} not found.");
    }

    // Step 4: Instantiate the class
    $classInstance = new $fullyQualifiedClassName();

    // Step 5: Check if method exists and is callable
    if (!method_exists($classInstance, $methodName)) {
        throw new Exception("Method {$methodName} not found in class {$fullyQualifiedClassName}.");
    }

    // Step 6: Call the method with parameters
    $result = call_user_func_array([$classInstance, $methodName], $params);

    // if ($result != 0) {
    //     throw new Exception("Job execution failed");
    // }

    return $result;
}

