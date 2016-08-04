@extends('layouts.panel')

@section('title', 'Links - ')

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset(Theme::url('plugins/iCheck/square/blue.css')) }}">
@endsection

@section('content')
    <section class="content-header">
        <h1>Categories <small>Create and manage site links</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ action("AdminController@index") }}"></a><i class="fa fa-dashboard"></i> Admin Panel</li>
            <li class="active">Links</li>
        </ol>
    </section>
    <section class="content">
        {!! $message_success !!}
        <div class="row">
            <div class="col-md-4">
                <form action="{{ action('Admin\LinkController@store') }}" method="POST">
                    {!! csrf_field() !!}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add a new Link</h3>
                        </div>
                        <div class="box-body">
                            {!! \AdminHelper::form_group()->title("Name *")->field("name")->errors($errors)->required()->render() !!}
                            {!! \AdminHelper::textField("Description", "desc", old("desc"), $errors) !!}
                            {!! \AdminHelper::form_group()->title("URL *")->field("url")->input_type("url")->errors($errors)->render() !!}
                            <div class="form-group">
                                <label for="sort_index">Sort Index</label>
                                <input type="number" class="form-control" name="sort_index" id="sort_index"
                                       value="{{ old('sort_index') }}">
                                <p class="help-block">Sort index of the link. Smaller the number, higher up in the
                                    list.</p>
                            </div>
                            <div class="form-group">
                                <label for="type">Item type</label>
                                <div class="btn-group btn-group-justified" data-toggle="buttons">
                                    <label for="" class="btn btn-default active">
                                        <input type="radio" name="type" value="link" checked> Link
                                    </label>
                                    <label for="" class="btn btn-default">
                                        <input type="radio" name="type" value="divider"> Divider
                                    </label>
                                </div>
                            </div>

                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-8">
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Links</h3>
                    </div>
                    <div class="box-body no-padding">
                        @foreach ($links as $l)
                            @if($l->type == "divider")
                                <div class="admin-link-divider admin-link-entry" data-id="{{ $l->id }}" data-type="divider">
                                    <div class="pull-right">
                                        <span class="admin-link-divider-index">{{ $l->sort_index }}</span>
                                        <button type="button" data-action="edit"
                                                class="btn btn-primary btn-xs">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <button type="button" data-action="delete" data-id="{{ $l->id }}"
                                                data-toggle="modal" data-target="#confirm-delete"
                                                data-name="{{ $l->name }}"
                                                data-href="{{ action('Admin\LinkController@destroy', ['id' => $l->id]) }}"
                                                class="btn btn-danger btn-xs">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                    <span class="admin-link-divider-name">{{ $l->name }}</span>
                                </div>
                            @elseif ($l->type == "link")
                                <div class="admin-link-item admin-link-entry" data-id="{{ $l->id }}" data-type="link">
                                    <div class="admin-link-item-favicon"><img
                                                src="//www.google.com/s2/favicons?domain_url={{ urlencode($l->url) }}"
                                                alt="{{ $l->name }} ({{ $l->url }})"></div>
                                    <div class="admin-link-item-info">
                                        <div class="admin-link-item-title">
                                            <a href="{{ $l->url }}" class="admin-link-item-name">{{ $l->name }}</a>
                                            <span class="admin-link-item-url">({{ $l->url }})</span>
                                        </div>
                                        <div class="admin-link-item-desc">{{ $l->desc }}</div>
                                    </div>
                                    <div class="admin-link-item-right">
                                        <span class="admin-link-item-index">{{ $l->sort_index }}</span>
                                        <button type="button" data-action="edit"
                                                class="btn btn-primary btn-xs">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <button type="button" data-action="delete" data-id="{{ $l->id }}"
                                                data-toggle="modal" data-target="#confirm-delete"
                                                data-name="{{ $l->name }}"
                                                data-href="{{ action('Admin\LinkController@destroy', ['id' => $l->id]) }}"
                                                class="btn btn-danger btn-xs">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal modal-danger" id="confirm-delete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="javascript:void(0)" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-close"></i>
                    </a>
                    <h4 class="modal-title">Confirm delete?</h4>
                </div>
                <div class="modal-body">
                    <p>Confirm to delete <span id="modal-item-name"></span>?</p>
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0)" class="btn btn-outline pull-left" data-dismiss="modal" aria-label="Close">Cancel</a>
                    <a href="javascript:void(0)" id="modal-btn-delete" class="btn btn-outline">Delete</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="{{ asset(Theme::url('js/links-inline-edit.js')) }}" id="links-inline-edit"
            data-edit-url="{{ action('Admin\LinkController@update', '%s') }}"></script>
    <script src="{{ asset(Theme::url('js/strechy.js')) }}" data-stretchy-filter=".inline-edit"></script>
    <script>
        $(function (){
            $("#confirm-delete")
                    .on("show.bs.modal", function (e) {
                        var data = $(e.relatedTarget).data();
                        $("#modal-item-name", this).text(data.name);
                        $("#modal-btn-delete", this).data('href', data.href);
                    })
                    .on("click", "#modal-btn-delete", function () {
                        $.ajax({url: $(this).data('href'), method: "DELETE"})
                                .then(function(){location.reload();});
                    });
        });
    </script>
@endsection