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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ asset(Theme::url('css/app.css')) }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700|Work+Sans:100,300,700" rel="stylesheet">

    <script src="bower_components/angular/angular.js"></script>
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/ngFitText/dist/ng-FitText.min.js"></script>
    {{--<script src="bower_components/angular-route/angular-route.js"></script>--}}
    <script src="bower_components/angular-ui-router/release/angular-ui-router.min.js"></script>
    <script src="bower_components/angular-animate/angular-animate.js"></script>
    <script src="bower_components/angular-resource/angular-resource.js"></script>
    <script src="app.js"></script>


    {{--<script src="//rawgithub.com/davatron5000/FitText.js/master/jquery.fittext.js"></script>--}}

</head>
<body ng-cloak ng-view ui-view>
</body>
</html>
