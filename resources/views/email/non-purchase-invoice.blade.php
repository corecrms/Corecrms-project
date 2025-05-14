
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Non Purchase Payment </title>
    <style>
        .fw-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <p>Dear {{ $name }},</p>
    <p>
        Non Purchase Payment has been made successfully. Paying amount is <span class="fw-bold">${{ number_format($amount_pay,2) }}</span>
    </p>
    <p>Best Regards,</p>
    <p>
        {{ getSetting()?->company_name ?? 'Company Name' }}
    </p>

</body>
</html>
