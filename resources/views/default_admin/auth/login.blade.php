@extends('layouts.base')

@section('body_class', 'login-page')

@section('body')
<div class="login-box">
    <div class="login-logo">
        {{ App\Config::getConfig('site_name') }} <b>Eanois CMS</b>
    </div>
    <div class="login-box-body">
        <p class="login-box-msg">Hey, who are you?</p>

        <form action="{{ action('Auth\AuthController@login') }}" role="form" method="POST">
            {!! csrf_field() !!}
            <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}">
                <span class="glyphicon form-control-feedback glyphicon-envelope"></span>
                @if ($errors->has('email'))
                    <span class="help-block">
                         <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                <input type="password" class="form-control" name="password" placeholder="Password" value="{{ old('password') }}">
                <span class="glyphicon form-control-feedback glyphicon-lock"></span>
                @if ($errors->has('password'))
                    <span class="help-block">
                         <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label for="remember">
                            <input type="checkbox" name="remember"> Remember Me
                        </label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <button class="btn btn-primary btn-block btn-flat" type="submit">Sign In</button>
                </div>
            </div>
        </form>
        <a href="{{ action('Auth\PasswordController@reset') }}">Forgot your password?</a>
    </div>
</div>
@endsection

@section('js')
    @parent
    <script src="{{ asset(Theme::url('plugins/iCheck/icheck.min.js')) }}"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
@endsection