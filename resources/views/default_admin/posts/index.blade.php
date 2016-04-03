@extends('layouts.panel')

@section('title', 'Posts - ')

@section('content')
    <section class="content-header">
        <h1>Posts <small>List of posts</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ action("AdminController@index") }}"></a><i class="fa fa-dashboard"></i> Admin Panel</li>
            <li class="active">Posts list</li>
        </ol>
    </section>
    <section class="content">
        {{ dump($posts) }}
    </section>
@endsection