<!-- resources/views/jobs/show.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details</title>
</head>
<body>
    <h1>Job Details</h1>

    @if ($job)
        <ul>
            <li><strong>ID:</strong> {{ $job['id'] }}</li>
            <li><strong>Class:</strong> {{ $job['class'] }}</li>
            <li><strong>Method:</strong> {{ $job['method'] }}</li>
            <li><strong>Args:</strong> {{ $job['args'] }}</li>
            <li><strong>Status:</strong> {{ $job['status'] }}</li>
            <li><strong>Created At:</strong> {{ $job['created_at'] }}</li>
            <li><strong>Updated At:</strong> {{ $job['updated_at'] }}</li>
        </ul>
    @else
        <p>Job not found</p>
    @endif
</body>
</html>
