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


Install dependencies:

bash
Copiar código
composer install
Configure your environment:

Copy .env.example to .env:
bash
Copiar código
cp .env.example .env
Set the SQLite database path in .env:
dotenv
Copiar código
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/jobs.db
Prepare the SQLite database:

Create the SQLite file:
bash
Copiar código
touch /absolute/path/to/database/jobs.db
Run migrations:
bash
Copiar código
php artisan migrate



Monitoring Job Status
Job statuses are logged in the SQLite database. To check the status:

Open the database using a tool like sqlite3:
bash
Copiar código
sqlite3 /absolute/path/to/database/jobs.db
Query the jobs table:
sql
Copiar código
SELECT * FROM jobs ORDER BY created_at ASC;



Configuration
Approved Classes
Only pre-approved classes can run as jobs for security purposes. Update the allowedClasses array in App\Runner\BackgroundJobService:

php
Copiar código
protected $allowedClasses = [
    'App\Jobs\MyJobClass',
    'App\Jobs\AnotherJobClass',
];


Key Components
Artisan Command: JobRunner
Located in app/Console/Commands/JobRunner.php, this command accepts a class, method, and optional parameters for execution.

SQLite Database
The jobs table in the SQLite database tracks job statuses and logs. Fields include:

id: Unique job ID.
class: The class being executed.
method: The method being executed.
params: Parameters passed to the method.
status: Status of the job (pending, completed, or failed).
created_at: Timestamp when the job was created.
