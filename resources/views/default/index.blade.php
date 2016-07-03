<!doctype html>
<html lang="en" ng-app="eanoisFrontEnd">
<head>
    <base href="{{ asset(Theme::url('/')) }}/">
    <meta charset="utf-8">
    <style>
        [ng-cloak] {
            display: none;
        }
    </style>
    <title>1A23 Studio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset(Theme::url('css/app.css')) }}">

    <script src="bower_components/angular/angular.js"></script>
    <script src="bower_components/angular-route/angular-route.js"></script>
    <script src="bower_components/angular-animate/angular-animate.js"></script>
    <script src="bower_components/angular-resource/angular-resource.js"></script>
    <script src="app.js"></script>
</head>
<body ng-cloak ng-view>
</body>
</html>
