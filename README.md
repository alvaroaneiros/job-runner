# Laravel Job Runner

This project is a Laravel-based system for managing and running background jobs. It provides a custom Artisan command (`job:run`) to execute specified methods of PHP classes with optional parameters. Job statuses and logs are maintained using an SQLite database for simplicity.

## Features

- **Custom Artisan Command**: Execute specific class methods with optional parameters.
- **SQLite Integration**: Job statuses and logs are stored in an SQLite database for ease of use.
- **Background Processing**: Jobs are run asynchronously.
- **Error Handling**: Captures and logs errors during job execution.
- **Security**: Ensures only pre-approved classes can be executed.

---

## Installation

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd <project-directory>


# Job Runner Setup Guide

## 1. Install Dependencies

Run the following command to install the necessary dependencies for the project:

```bash
composer install


## Configure Your Environment

1. **Copy `.env.example` to `.env`:**

    ```bash
    cp .env.example .env
    ```

2. **Set the SQLite database path in `.env`:**

    Open `.env` and add the following line to set the database path:

    ```dotenv
    DB_CONNECTION=sqlite
    DB_DATABASE=/absolute/path/to/database/jobs.db
    ```
## Configuration: Approved Classes

Only pre-approved classes can run as jobs for security purposes. Update the `allowedClasses` array in `App\Runner\BackgroundJobService`:

```php
protected $allowedClasses = [
    'App\Jobs\MyJobClass',
    'App\Jobs\AnotherJobClass',
];


## Key Components

### Artisan Command: JobRunner

The `JobRunner` Artisan command is located in `app/Console/Commands/JobRunner.php`. This command accepts a class, method, and optional parameters for execution.

### SQLite Database

The `jobs` table in the SQLite database tracks job statuses and logs. Fields include:

- **id**: Unique job ID.
- **class**: The class being executed.
- **method**: The method being executed.
- **params**: Parameters passed to the method.
- **status**: Status of the job (pending, completed, or failed).
- **created_at**: Timestamp when the job was created.
