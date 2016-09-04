<!doctype html>
<html lang="en" ng-app="eanoisFrontEnd">
<head>
    <base href="/">
    <meta name="fragment" content="!">
    <meta name="theme_root" content="{{ asset(Theme::url('/')) }}">
    <meta name="site:name" content="{{ $setting['site_name'] }}">
    <meta name="site:desc" content="{{ $setting['site_desc'] }}">
    <meta name="site:logo" content="{{ $setting['site_logo_url'] }}">
    <meta charset="utf-8">

    <!-- Search engines -->
    <meta name="description" content="@{{ ngMeta.description }}">
    <!-- Google Plus -->
    <meta itemprop="name" content="@{{ ngMeta.title }}">
    <meta itemprop="description" content="@{{ ngMeta.description }}">
    <meta itemprop="image" content="@{{ ngMeta.image }}">
    <!-- Twitter -->
    <meta name="twitter:card" content="@{{ ngMeta['twitter:card'] }}">
    <meta name="twitter:title" content="@{{ ngMeta.title }}">
    <meta name="twitter:description" content="@{{ ngMeta.description }}">
    <meta name="twitter:image:src" content="@{{ ngMeta.image }}">
    <!-- Open Graph General (Facebook & Pinterest) -->
    <meta property="og:url" content="">
    <meta property="og:title" content="@{{ ngMeta.title }}">
    <meta property="og:description" content="@{{ ngMeta.description }}">
    <meta property="og:site_name" content="{{ $setting['site_name'] }}">
    <meta property="og:image" content="@{{ ngMeta.image }}">
    <meta property="og:type" content="@{{ ngMeta['og:type'] }}">

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
    <script src="{{ asset(Theme::url('/')) }}/bower_components/ngMeta/dist/ngMeta.min.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/angular-ui-router/release/angular-ui-router.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/angular-animate/angular-animate.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/angular-resource/angular-resource.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/angular-sanitize/angular-sanitize.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/fld-grd/dist/fldGrd.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/ngInfiniteScroll/build/ng-infinite-scroll.min.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/js/ngFldGrd.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/js/app.js"></script>


    {{--<script src="//rawgithub.com/davatron5000/FitText.js/master/jquery.fittext.js"></script>--}}

</head>
<body ng-cloak ng-view ui-view>
</body>
</html>
