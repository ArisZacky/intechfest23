<!DOCTYPE html>
<html lang="en">

<head>
    @include('landing.layout.head')
    <title>@yield('title')</title>
</head>

<body style="font-family: 'Plus Jakarta Sans','sans-serif';" class="overflow-x-hidden">
    @yield('content')
    @include('landing.layout.footer')
    @yield('scripts')
    @include('landing.layout.scripts')
</body>

</html>