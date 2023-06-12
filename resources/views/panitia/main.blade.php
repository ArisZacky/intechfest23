<!DOCTYPE html>
<html lang="en">

<head>
    @include('panitia.layout.head')
    <title>@yield('title')</title>
</head>

<body style="font-family: 'Plus Jakarta Sans','sans-serif';">
    @include('panitia.layout.navbar')
    @include('panitia.layout.sidebar')
    @yield('content')
    @include('panitia.layout.scripts')
</body>

</html>