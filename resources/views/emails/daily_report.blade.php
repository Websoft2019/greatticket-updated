<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daily Sales Report</title>
</head>
<body>
    <h2>Daily Report</h2>
    <p>Here is your daily report:</p>
    <ul>
        @foreach($reportData as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>
</body>
</html>