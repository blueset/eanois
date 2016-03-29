<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title'){{ App\Setting::getConfig('site_name') }}</title>
</head>
<body>
    @yield('content')
</body>
</html>