<!DOCTYPE html>
<html lang="en">

<head>
    @include('landing.layout.head')
    <title>@yield('title')</title>
</head>

<body class="font-Plus-Jakarta-Sans overflow-x-hidden">
    @yield('content')
    @include('landing.layout.footer')
    @yield('scripts')
    @include('landing.layout.scripts')
</body>

</html>