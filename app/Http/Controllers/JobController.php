<?php

namespace App\Http\Controllers;

use App\Runner\JobDatabaseManager;
use Illuminate\Http\Request;

class JobController extends Controller
{
    protected $jobManager;

    public function __construct(JobDatabaseManager $jobManager)
    {
        $this->jobManager = $jobManager;
    }

    public function index()
    {
        // Retrieve all pending jobs
        $pendingJobs = $this->jobManager->getAllJobs();

        // Pass the pending jobs data to the view
        return view('jobs.index', ['jobs' => $pendingJobs]);
    }

    public function show($id)
    {
        // Retrieve job by ID
        $job = $this->jobManager->getJobById($id);

        if ($job) {
            // Pass the job data to the view
            return view('jobs.show', ['job' => $job]);
        }

        return redirect()->route('jobs.index')->with('error', 'Job not found');
    }
}
