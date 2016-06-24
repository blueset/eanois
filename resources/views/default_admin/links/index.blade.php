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
                <form action="{{ action('Admin\CategoryController@store') }}" method="POST">
                    {!! csrf_field() !!}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Create a new category</h3>
                        </div>
                        <div class="box-body">
                            {!! \AdminHelper::textField("Name *", "name", old("name"), $errors) !!}
                            {!! \AdminHelper::textField("Slug", "slug", old("slug"), $errors) !!}
                            <div class="form-group">
                                <label for="template">Template</label>
                                <input type="text" class="form-control" name="template" id="template" value="{{ old('template') }}">
                                <p class="help-block">The theme used for listing page of the category.</p>
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
                        <h3 class="box-title">Categories</h3>
                    </div>
                    <div class="box-body no-padding">
                        <table class="table">
                            <tbody>
                            <tr>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Template</th>
                                <th>Posts</th>
                                <th class="table-action"></th>
                            </tr>
                            @foreach( $categories as $cat )
                                <tr>
                                    <td><a href="{{ action('Admin\CategoryController@show', ['id' => $cat->id]) }}">{{ $cat->name }}</a></td>
                                    <td><code>{{ $cat->slug }}</code></td>
                                    <td><code>{{ $cat->template }}</code></td>
                                    <td>{{ $cat->posts()->count() }}</td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#confirm-delete" data-name="{{ $cat->name }} ({{ $cat->slug }})" data-href="{{ action('Admin\CategoryController@destroy', ['id' => $cat->id]) }}">
                                            <i class="fa fa-remove"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
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