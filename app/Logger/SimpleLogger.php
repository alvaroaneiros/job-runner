<?php

namespace App\Logger;

class SimpleLogger
{
    private $logFilePath;
    private $logLevels = ['NOTICE', 'WARNING', 'ERROR'];


    public function setOutputFile($filePath)
    {
        $this->logFilePath = 'logs/'.$filePath;
        // Ensure the log file is writable or create it if it doesn't exist
        $directoryPath = 'logs';
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }
        
        if (!file_exists($this->logFilePath)) {
            file_put_contents($this->logFilePath, ''); // create the file
        }
    }

    // Helper methods for specific log levels
    public function notice($message, $context = [])
    {
        $this->log('NOTICE', $message, $context);
    }

    public function warning($message, $context = [])
    {
        $this->log('WARNING', $message, $context);
    }

    public function error($message, $context = [])
    {
        $this->log('ERROR', $message, $context);
    }
        
    private function log($level, $message, $context = [])
    {
        // Check if the provided level is valid
        if (!in_array($level, $this->logLevels)) {
            throw new InvalidArgumentException('Invalid log level: ' . $level);
        }

        // Format the message with context data if available
        $formattedMessage = $this->formatMessage($level, $message, $context);

        // Write the formatted message to the log file
        file_put_contents($this->logFilePath, $formattedMessage, FILE_APPEND);
    }

    private function formatMessage($level, $message, $context)
    {
        $dateTime = new \DateTime();
        $formattedDate = $dateTime->format('Y-m-d H:i:s');
        $contextString = json_encode($context);

        return sprintf("[%s] %s: %s %s\n", $formattedDate, $level, $message, $contextString);
    }

}

