<?php

namespace App\Jobs;

use Exception;

class MyJobClass
{
    /**
     * Process job with given parameters.
     *
     * @param mixed ...$params Parameters passed to the method.
     * @return string
     * @throws Exception If an error occurs during job processing.
     */
    public function processJob(...$params)
    {
        // Example task - process each parameter
        foreach ($params as $param) {
            // Simulate some processing
            if (empty($param)) {
                throw new Exception("Invalid parameter provided.");
            }
        }
        
        // Example successful result
        return "Processed job with parameters: " . implode(', ', $params);
    }

    public function processJob2(...$params)
    {
        // Example task - process each parameter
        foreach ($params as $param) {
            // Simulate some processing
            if (empty($param)) {
                throw new Exception("Invalid parameter provided.");
            }
        }
        
        // Example successful result
        return json_encode($params);
    }

}
