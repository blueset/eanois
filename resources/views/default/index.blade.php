<!doctype html>
<html lang="en" ng-app="eanoisFrontEnd">
<head>
    <base href="/">
    <meta name="fragment" content="!">
    <meta name="theme_root" content="{{ asset(Theme::url('/')) }}">
    <meta name="site:name" content="{{ $setting['site_name'] }}">
    <meta name="site:desc" content="{{ $setting['site_desc'] }}">
    <meta name="site:logo" content="{{ $setting['site_logo_url'] }}">

    <link rel="alternate" type="application/atom+xml" title="RSS (Atom) Feed for {{ $setting['site_name'] }}" href="/feed.xml" />

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
    <meta property="og:url" content="{{ url()->full() }}">
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
    <title ng-bind="$title">1A23 Studio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ asset(Theme::url('css/app.css')) }}">

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset(Theme::url('/')) }}/favicons/apple-touch-icon-57x57.png?v=201512180001">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset(Theme::url('/')) }}/favicons/apple-touch-icon-60x60.png?v=201512180001">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset(Theme::url('/')) }}/favicons/apple-touch-icon-72x72.png?v=201512180001">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset(Theme::url('/')) }}/favicons/apple-touch-icon-76x76.png?v=201512180001">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset(Theme::url('/')) }}/favicons/apple-touch-icon-114x114.png?v=201512180001">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset(Theme::url('/')) }}/favicons/apple-touch-icon-120x120.png?v=201512180001">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset(Theme::url('/')) }}/favicons/apple-touch-icon-144x144.png?v=201512180001">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset(Theme::url('/')) }}/favicons/apple-touch-icon-152x152.png?v=201512180001">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset(Theme::url('/')) }}/favicons/apple-touch-icon-180x180.png?v=201512180001">
    <link rel="icon" type="image/png" href="{{ asset(Theme::url('/')) }}/favicons/favicon-32x32.png?v=201512180001" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset(Theme::url('/')) }}/favicons/android-chrome-192x192.png?v=201512180001" sizes="192x192">
    <link rel="icon" type="image/png" href="{{ asset(Theme::url('/')) }}/favicons/favicon-96x96.png?v=201512180001" sizes="96x96">
    <link rel="icon" type="image/png" href="{{ asset(Theme::url('/')) }}/favicons/favicon-16x16.png?v=201512180001" sizes="16x16">
    <link rel="manifest" href="{{ asset(Theme::url('/')) }}/favicons/manifest.json?v=201512180001">
    <link rel="mask-icon" href="{{ asset(Theme::url('/')) }}/favicons/safari-pinned-tab.svg?v=201512180001" color="#579df0">
    <link rel="shortcut icon" href="{{ asset(Theme::url('/')) }}/favicons/favicon.ico?v=201512180001">
    <meta name="apple-mobile-web-app-title" content="1A23 Studio">
    <meta name="application-name" content="1A23 Studio">
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="msapplication-TileImage" content="{{ asset(Theme::url('/')) }}/favicons/mstile-144x144.png?v=201512180001">
    <meta name="theme-color" content="#333333">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700|Work+Sans:100,200,300,700" rel="stylesheet">

    <script src="{{ asset(Theme::url('/')) }}/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/angular/angular.min.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/ngFitText/dist/ng-FitText.min.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/ngMeta/dist/ngMeta.min.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/angular-ui-router/release/angular-ui-router.min.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/angular-animate/angular-animate.min.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/angular-resource/angular-resource.min.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/angular-sanitize/angular-sanitize.min.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/js/fldGrd.min.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/ngInfiniteScroll/build/ng-infinite-scroll.min.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/gsap/src/minified/TweenMax.min.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/bower_components/angular-gsapify-router/angular-gsapify-router.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/js/ngFldGrd.js"></script>
    <script src="{{ asset(Theme::url('/')) }}/js/app.js"></script>


    {{--<script src="//rawgithub.com/davatron5000/FitText.js/master/jquery.fittext.js"></script>--}}

</head>
<body>
<div ng-show="stateLoading" class="spinner-wrapper">
    <svg class="spinner" width="25px" height="25px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
        <circle class="spinner-path" fill="none" stroke-width="8" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
    </svg>
</div>
<div ng-view ui-view class="gsapify-router" autoscroll="false" scroll-recall></div>
{!! \App\Setting::getConfig('analytics_code'); !!}
</body>
</html>
