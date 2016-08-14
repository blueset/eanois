@extends('layouts.panel')

@section('title', 'Settings - ')

@section('content')
    <section class="content-header">
        <h1>Settings <small>Eanois Content Management System</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ action("AdminController@index") }}"><i class="fa fa-dashboard"></i> Admin Panel</a></li>
            <li class="active">Settings</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-lg-8">
                {!! $message_success !!}
                <form action="{{ action("AdminController@putSettings") }}" method="POST" role="form">
                    <input type="hidden" name="_method" value="PUT">
                    {!! csrf_field() !!}
                    <div class="box">
                        <div class="box-body">
                            {!! AdminHelper::textField("Site name", "site_name", array_get($config, 'site_name', '')) !!}
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                Feeds
                            </h3>
                        </div>
                        <div class="box-body">
                            {!! AdminHelper::form_group()
                                ->title("Feed URL")
                                ->field("feed_url")
                                ->value(array_get($config, 'feed_url', ''))
                                ->desc("URLs of feeds to be included in the “Last Updates” section. Leave blank to disable. Separate URLs with a space.") !!}
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </section>
@endsection