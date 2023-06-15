<!DOCTYPE html>
<html lang="en">

<head>
    @include('peserta.layout.head')
    <title>@yield('title')</title>
</head>

<body style="font-family: 'Plus Jakarta Sans','sans-serif';">
    @include('peserta.layout.navbar')
    @include('peserta.layout.sidebar')
    @yield('content')
    @include('peserta.layout.scripts')
</body>

</html>