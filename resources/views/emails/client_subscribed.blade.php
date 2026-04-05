<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectLine }}</title>
</head>
<body>
    <h1>{{ $subjectLine }}</h1>
    <p>{{ $summary }}</p>
    <p><strong>Client Name:</strong> {{ $client->user->name }}</p>
    <p><strong>Client Email:</strong> {{ $client->user->email }}</p>
    <p><strong>Phone:</strong> {{ $client->user->phone_country_code }} {{ $client->user->phone_number }}</p>
    <p><strong>Plan:</strong> {{ $client->subscriptions()->latest('id')->first()?->plan?->name ?? 'N/A' }}</p>
    <p><strong>Status:</strong> {{ $client->onboarding_status }}</p>
    <p>Please assign an admin and start the support process.</p>
    <p>Best regards,<br>RifiMedia System</p>
</body>
</html>
