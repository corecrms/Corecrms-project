
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tracking No</title>
    <style>
        .fw-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <p>Dear {{ $name }},</p>
    <p>
        Your tracking number is: <span class="fw-bold">{{ $trackingNo }}</span> of your  <span class="fw-bold">{{ $reference }}</span>.
    </p>
    <p>Thank you for shopping with us.</p>
    <p>Best Regards,</p>
    <p>
        {{ getSetting()?->company_name ?? 'Company Name' }}
    </p>

</body>
</html>
