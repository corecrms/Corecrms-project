{{-- @component('mail::message')
# Introduction

The body of your message.

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent --}}


<!DOCTYPE html>
<html>
<head>
    <title>Account Approval</title>
</head>
<body style="font-size: 20px">
    <h1>Hello, {{ $user->name }}</h1>
    <p>We are pleased to inform you that your account has been approved.</p>
    <p>You can now log in and start using our services.</p>
    <p>Thank you for joining us!</p>
    <p>Best regards,<br>{{ config('app.name') }}</p>
</body>
</html>
