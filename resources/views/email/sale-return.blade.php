
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
        The return was received and store credit applied. You can use the store credit to purchase any item from our store.
    </p>
    <p>Best Regards,</p>
    <p>
        {{ getSetting()?->company_name ?? 'Company Name' }}
    </p>

</body>
</html>
