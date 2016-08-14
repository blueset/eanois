@extends('layouts.panel')

@section('title', 'Tags - Posts - ')

@section('css')
    @parent
@endsection

@section('content')
    <section class="content-header">
        <h1>Tags <small>Create and manage tags for posts</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ action("AdminController@index") }}"><i class="fa fa-dashboard"></i> Admin Panel</a></li>
            <li><a href="{{ action('Admin\PostController@index') }}"> Posts</a></li>
            <li class="active">Tags</li>
        </ol>
    </section>
    <section class="content">
        {!! $message_success !!}
        <div class="row">
            <div class="col-md-8">
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Tags</h3>
                    </div>
                    <div class="box-body">
                        <div class="tags" data-type="tags" data-min="1" data-max="2.5">
                            @foreach($tags as $t)
                                <a href="{{ action('Admin\TagController@show', ['id' => $t->id]) }}" data-type="tag" data-count="{{ $t->posts()->count() }}" class="tag-link">{{ $t->name }}</a>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <form action="{{ action('Admin\TagController@store') }}" method="POST">
                    {!! csrf_field() !!}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Create a new tag</h3>
                        </div>
                        <div class="box-body">
                            {!! \AdminHelper::textField("Name *", "name", old("name"), $errors) !!}
                            {!! \AdminHelper::textField("Slug", "slug", old("slug"), $errors) !!}
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                </form>
            </div>


        </div>
    </section>

@endsection

@section('js')
    @parent
    <script src="{{ asset(Theme::url('js/jquery-tagcloud.js')) }}"></script>
    <script>
        $(function() {

        });
    </script>
@endsection