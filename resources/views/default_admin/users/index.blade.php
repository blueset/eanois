@extends('layouts.panel')

@section('title', 'Users - ')

@section('content')
    <section class="content-header">
        <h1>Users <small>Administrators and contributors</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ action("AdminController@index") }}"><i class="fa fa-dashboard"></i> Admin Panel</a></li>
            <li class="active">Users</li>
        </ol>
    </section>
    <section class="content">
        {!! $message_success !!}
        <div class="row">
            @foreach($users as $user)
                <div class="col-md-4">
                    <?php $meta = $user->getMeta(); ?>
                    <div class="box box-widget widget-user-2">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header bg-light-blue">
                            <div class="widget-user-image">
                                <img class="img-circle" src="{{ Gravatar::get($user->email, ['size' => 128]) }}" alt="User Avatar">
                            </div>
                            <!-- /.widget-user-image -->
                            <h3 class="widget-user-username">{{ $meta['display_name'] }}</h3>
                            <h5 class="widget-user-desc">ID: {{ $user->name }}</h5>
                        </div>
                        <div class="box-footer no-padding">
                            <ul class="nav nav-stacked">
                                <li><a href="{{ $meta['url'] }}">URL <span class="pull-right">{{ $meta['url'] }}</span></a></li>
                                <li><a href="javascript:void(0)">Email <span class="pull-right">{{ $user->email }}</span></a></li>
                                <li><a href="{{ action('Admin\UserController@edit', $user->id) }}">Edit details</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection

@section('js')
    @parent
@endsection