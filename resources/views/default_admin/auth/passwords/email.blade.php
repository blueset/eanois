@extends('layouts.base')


@section('body_class', 'login-page')

@section('body')
    <div class="login-box">
        <div class="login-logo">
            {{ App\Config::getConfig('site_name') }} <b>Eanois CMS</b>
        </div>

        @if (session('status'))
            <div class="callout callout-success">
                <p>{{ session('status') }}</p>
            </div>
        @endif

        <div class="login-box-body">
            <p class="login-box-msg">Forgot your password already, huh?</p>

            <form action="{{ action('Auth\PasswordController@sendResetLinkEmail') }}" role="form" method="POST">
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
                <button class="btn btn-primary btn-block btn-flat" type="submit">Send Password Reset Link</button>
                </div>
            </form>
        </div>
    </div>
@endsection

<!-- Main Content -->
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-envelope"></i>Send Password Reset Link
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
