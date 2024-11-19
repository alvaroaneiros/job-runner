<!-- resources/views/jobs/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Jobs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #007bff;
        }
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #dee2e6;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        table td {
            background-color: #ffffff;
        }
        .alert {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8d7da;
            color: #721c24;
            border-radius: 5px;
        }
        .btn-refresh {
            margin-top: 20px;
            display: block;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Jobs</h1>

        @if (session('error'))
            <div class="alert">
                {{ session('error') }}
            </div>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Class</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jobs as $job)
                    <tr>
                        <td>{{ $job['id'] }}</td>
                        <td>{{ $job['class'] }}</td>
                        <td>{{ $job['method'] }}</td>
                        <td>
                            <span class="badge bg-{{ $job['status'] == 'pending' ? 'warning' : 'success' }}">
                                {{ ucfirst($job['status']) }}
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($job['created_at'])->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Optional refresh button -->
        <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-refresh">Refresh Jobs</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
