<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>@yield('title')Eanois CMS</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset(Theme::url('css/bootstrap.min.css')) }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset(Theme::url('css/AdminLTE.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(Theme::url('plugins/iCheck/square/blue.css')) }}">
</head>
<body class="hold-transition @yield('body_class')">
@yield('body')
@section('js')
    <script src="{{ asset(Theme::url('plugins/jQuery/jQuery-2.2.0.min.js')) }}"></script>
    <script src="{{ asset(Theme::url('js/bootstrap.min.js')) }}"></script>
@show
</body>
</html>