<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.layout.head')
    <title>@yield('title')</title>
</head>

<body style="font-family: 'Plus Jakarta Sans','sans-serif';">
    @include('admin.layout.navbar')
    @include('admin.layout.sidebar')
    @yield('content')
    @include('admin.layout.scripts')
</body>

</html>