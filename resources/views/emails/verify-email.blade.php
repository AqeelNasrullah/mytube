<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>* {padding: 0xp;margin: 0px;font-family: sans-serif;}</style>
</head>
<body>
    <div style="background-color: rgba(245,245,245,1.00); padding: 15px;">
        <div style="width: auto !important;margin-bottom: 30px;display: flex;justify-content: center;">
            <div style="margin-right:15px;float: left; width: 50px;"><img src="{{ asset('images/mytube.webp') }}" width="100%" alt="Logo not found"></div>
            <h1 style="font-size: 35px;padding-top: 5px;">{{ config('app.name') }}</h1>
            <br style="clear: both;">
        </div>

        <div style="max-width: 600px;border-radius: 5px;margin:0px auto;background-color: white;padding: 15px;margin-bottom: 30px;">
            <h1 style="font-size: xx-large;margin-bottom: 15px;">Verify Email</h1>
            <h3 style="font-size: x-large;">Hello, {{ $data['email'] }}</h3>
            <h5 style="font-size: large">Tank You for registering with us!</h5>
            <p style="font-size: medium; margin-bottom: 15px;">You are almost ready to start. Simply click on the button below to verify your email address.</p>

            <p style="margin-bottom: 15px;width: 150px;padding: 10px 20px;background-color: red;border-radius: 10px;"><a href="{{ route('verifyEmail.index', [$data['user'], $data['key']]) }}" style="display: block;text-align: center; text-decoration: none;color: white;">Verify Email</a></p>

            <p style="font-size: medium; margin-bottom: 15px;">Didn't Work? Copy link given below and paste in your browser.</p>

            <p style="margin-bottom: 15px;"><a href="{{ route('verifyEmail.index', [$data['user'], $data['key']]) }}" style="overflow-wrap: break-word;">{{ route('verifyEmail.index', [$data['user'], $data['key']]) }}</a></p>

            <p>Regards,</p>
            <p>{{ config('app.name') }} Team</p>
        </div>

        <p style="text-align: center;">Copyrights &copy; {{ date('Y') }} - All Rights Reserved.</p>
    </div>
</body>
</html>
