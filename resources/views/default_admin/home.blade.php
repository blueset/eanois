@extends('layouts.panel')

@section('title', 'Dashboard - ')

@section('content')
<section class="content-header">
    <h1>Dashboard <small>Eanois Content Management System</small></h1>
    <ol class="breadcrumb">
        <li><a href=""></a><i class="fa fa-dashboard"></i> Admin Panel</li>
        <li class="active">Dashboard</li>
    </ol>
</section>
<section class="content">
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus accusantium ad aperiam atque, dolore doloribus eaque, eos eveniet ex facere fugiat odio pariatur quasi qui quia quis sint suscipit tempora.</p>
    <p>{{ dump($user->name) }}</p>
</section>
@endsection