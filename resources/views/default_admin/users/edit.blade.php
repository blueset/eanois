@extends('layouts.panel')

@section('title', 'Users - ')

@section('content')
    <section class="content-header">
        <h1>Edit user <small>Edit a user and its properties</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ action("AdminController@index") }}"><i class="fa fa-dashboard"></i> Admin Panel</a></li>
            <li><a href="{{ action('Admin\UserController@index') }}">Users</a></li>
            <li class="active">Edit</li>
        </ol>
    </section>
    <section class="content">
        {!! $message_success !!}
        <div class="row">
            <div class="col-md-6 col-md-push-3">

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Editing user with ID {{ $user->id }}</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    @include("layouts.validation_error")
                    <form action="{{ action('Admin\UserController@update', ['id' => $user->id]) }}" class="form-horizontal" method="post">
                        <input type="hidden" name="_method" value="PUT">
                        {!! csrf_field() !!}
                        <div class="box-body">
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">Username</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" class="form-control" value="{{ old("name", $user->name) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Email</label>

                                <div class="col-sm-9">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old("email", $user->email) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="meta[display_name]" class="col-sm-3 control-label">Display name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="meta[display_name]" class="form-control" value="{{ old("display_name", $meta['display_name']) }}" placeholder="Display name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="meta[url]" class="col-sm-3 control-label">URL</label>
                                <div class="col-sm-9">
                                    <input type="text" name="meta[url]" class="form-control" value="{{ old("url", $meta['url']) }}" placeholder="URL">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-sm-3 control-label">Current Password</label>

                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="new_password" class="col-sm-3 control-label">New password</label>

                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="confirm_new_password" class="col-sm-3 control-label">Confirm new password</label>

                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirm new password">
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right">Update</button>
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