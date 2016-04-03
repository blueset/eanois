@extends('layouts.panel')

@section('title', 'Settings - ')

@section('content')
    <section class="content-header">
        <h1>Settings <small>Eanois Content Management System</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ action("AdminController@index") }}"></a><i class="fa fa-dashboard"></i> Admin Panel</li>
            <li class="active">Settings</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-lg-8">
                {!! $message_success !!}
                <form action="{{ action("AdminController@putSettings") }}" method="POST" ole="form">
                    {!! csrf_field() !!}
                    {!! AdminHelper::textField("Site name", "site_name", array_get($config, 'site_name', '')) !!}
                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </section>
@endsection