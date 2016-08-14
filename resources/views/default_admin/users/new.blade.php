@extends('layouts.panel')

@section('title', 'Users - ')

@section('content')
    <section class="content-header">
        <h1>Create a user <small>Create a user and enter its properties</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ action("AdminController@index") }}"><i class="fa fa-dashboard"></i> Admin Panel</a></li>
            <li><a href="{{ action('Admin\UserController@index') }}">Users</a></li>
            <li class="active">New</li>
        </ol>
    </section>
    <section class="content">
        {!! $message_success !!}
        <div class="row">
            <div class="col-md-6 col-md-push-3">

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">New user</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    @include("layouts.validation_error")
                    <form action="{{ action('Admin\UserController@store') }}" class="form-horizontal" method="post">
                        <input type="hidden" name="_method" value="PUT">
                        {!! csrf_field() !!}
                        <div class="box-body">
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">Username</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" class="form-control" value="{{ old("name") }}" placeholder="Username">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Email</label>

                                <div class="col-sm-9">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old("email") }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="meta[display_name]" class="col-sm-3 control-label">Display name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="meta[display_name]" class="form-control" value="{{ old("display_name") }}" placeholder="Display name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="meta[url]" class="col-sm-3 control-label">URL</label>
                                <div class="col-sm-9">
                                    <input type="text" name="meta[url]" class="form-control" value="{{ old("url") }}" placeholder="URL">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-sm-3 control-label">Password</label>

                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation" class="col-sm-3 control-label">Confirm password</label>

                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="password_confirmation" name="new_password_confirmation" placeholder="Confirm password">
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right">Create</button>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    @parent
@endsection