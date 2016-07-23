<!doctype html>
<html lang="en" ng-app="eanoisFrontEnd">
<head>
    <base href="/">
    <meta name="theme_root" content="{{ asset(Theme::url('/')) }}">
    <meta charset="utf-8">
    <style>
        [ng-cloak] {
            display: none;
        }
    </style>
    <title ng-bind="$title">1A23.Studio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ asset(Theme::url('css/app.css')) }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700|Work+Sans:100,200,300,700" rel="stylesheet">

    <script src="{{ asset(Theme::url('/')) }}/bower_components/angular/angular.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/ngFitText/dist/ng-FitText.min.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/angular-ui-router/release/angular-ui-router.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/angular-animate/angular-animate.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/angular-resource/angular-resource.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/angular-sanitize/angular-sanitize.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/app.js"></script>


    {{--<script src="//rawgithub.com/davatron5000/FitText.js/master/jquery.fittext.js"></script>--}}

</head>
<body ng-cloak ng-view ui-view>
</body>
</html>
