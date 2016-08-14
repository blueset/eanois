@extends('layouts.base')

@section('body_class', 'fixed skin-blue sidebar-mini')

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset(Theme::url('css/skins/skin-blue.min.css')) }}">
@endsection

@section('body')
<div class="wrapper">
    <header class="main-header">
        <a href="{{ action('AdminController@index') }}" class="logo">
            <span class="logo-mini"><b>E</b>C</span>
            <span class="logo-lg"><b>Eanois</b> CMS</span>
        </a>

        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <?php $meta = $user->getMeta(); ?>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ Gravatar::get($user->email, ['size' => 200]) }}" alt="" class="user-image" alt="{{ $meta['display_name'] }}'s profile picture">
                            <span class="hidden-xs">{{ $meta['display_name'] }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="{{ Gravatar::get($user->email, ['size' => 200]) }}" alt="" class="img-circle">
                                <p>
                                    {{ $meta['display_name'] }}
                                    <small>Administrator</small>
                                </p>
                            </li>
                            {{--<li class="user-body">--}}
                                {{--<div class="row">--}}
                                    {{--<div class="col-xs-4 text-center"><a href="#">Link 1</a></div>--}}
                                    {{--<div class="col-xs-4 text-center"><a href="#">Link 2</a></div>--}}
                                    {{--<div class="col-xs-4 text-center"><a href="#">Link 3</a></div>--}}
                                {{--</div>--}}
                            {{--</li>--}}
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="@{{ action('dummyAction') }}" class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ action('Auth\AuthController@logout') }}" class="btn btn-default btn-flat">Sign Out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    @include('layouts.sidebar')
    <div class="content-wrapper">
        @yield('content')
    </div>
    <footer class="main-footer">
        <div class="pull-right hidden-xs">Version: {{ Config::get('eanois.version') }}</div>
        <strong>Eanois CMS is developed by <a href="https://1a23.com">1A23 Studio</a>.</strong> We create.
    </footer>
</div>
@endsection

@section('js')
    @parent
    <script src="{{ asset(Theme::url('js/app.min.js')) }}"></script>
@endsection