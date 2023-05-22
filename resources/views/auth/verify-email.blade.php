<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Verifikasi</title>
</head>
<style>
    .main{
        display: flex;
        justify-content: center;
    }
</style>
<body>
    <h1>Email sudah dikirim ke email anda, silahkan cek email anda !</h1>
    <form action="/email/verification-notification" method="POST">
        @csrf
        <h3>Belum mendapatkan email? <button type="submit">Kirim ulang email</button></h3>
    </form>
</body>
</html>