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
    {{--.row>section.col-lg-7.connectedSortable>.box.box-primary>(.box-header>i.zmdi.zmdi-info+h3.box-title)+.box-body+.box-footer.clearfix.no-border--}}
    <div class="row">
        <section class="col-lg-7 connectedSortable">
            <div class="box box-primary">
                <div class="box-header">
                    <i class="fa fa-info-circle"></i>
                    <h3 class="box-title">About Eanois CMS</h3>
                </div>
                <div class="box-body">
                    <p>Eanois is 1A23 Studio's original CMS written with PHP and laravel.</p>
                </div>
                <div class="box-footer clearfix no-border">
                    Version: {{ Config::get('eanois.version') }}
                </div>
            </div>
        </section>
    </div>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus accusantium ad aperiam atque, dolore doloribus eaque, eos eveniet ex facere fugiat odio pariatur quasi qui quia quis sint suscipit tempora.</p>
    <p>{{ dump($user->name) }}</p>
</section>
@endsection