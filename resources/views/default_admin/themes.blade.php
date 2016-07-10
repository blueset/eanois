@extends('layouts.panel')

@section('title', 'Templates - ')

@section('content')
    <section class="content-header">
        <h1>Themes <small>Front-end and back-end themes</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ action("AdminController@index") }}"></a><i class="fa fa-dashboard"></i> Admin Panel</li>
            <li class="active">Themes</li>
        </ol>
    </section>
    <section class="content">
        {!! $message_success !!}
        <div class="box box-success box-front">
            <div class="box-header">
                <h3 class="box-title">
                    Front-end themes
                </h3>
            </div>
            <div class="box-body">
                <div class="row">
                    @foreach($frontList as $item)
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="theme-box{{ $item == $front ? " selected" : "" }}" data-id="{{ $item }}" data-type="front">
                                <div class="theme-box-icon"><i class="fa fa-paint-brush"></i></div>
                                <div class="theme-box-title">
                                    <h3 class="theme-box-name">{{ $item }}</h3>
                                    <div class="theme-box-desc">Theme ID: {{ $item }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="box box-primary box-back">
            <div class="box-header">
                <h3 class="box-title">
                    Back-end themes
                </h3>
            </div>
            <div class="box-body">
                <div class="row">
                    @foreach($backList as $item)
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="theme-box{{ $item == $back ? " selected" : "" }}" data-id="{{ $item }}" data-type="back">
                                <div class="theme-box-icon"><i class="fa fa-paint-brush"></i></div>
                                <div class="theme-box-title">
                                    <h3 class="theme-box-name">{{ $item }}</h3>
                                    <div class="theme-box-desc">Theme ID: {{ $item }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    @parent
    <script>
        $(function (){
            $('.theme-box').click(function(){
                $.ajax({
                    url: "{{ action("AdminController@themeUpdate") }}",
                    method: "PUT",
                    data: {
                        type: $(this).data("type"),
                        id: $(this).data("id")
                    }
                }).then(function(){
                    location.reload();
                });
            });
        });
    </script>
@endsection