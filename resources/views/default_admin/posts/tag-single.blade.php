@extends('layouts.panel')

@section('title', $tag->name.' - Tag - Posts - ')

@section('css')
    @parent
@endsection

@section('content')
    <section class="content-header">
        <h1>Tag: <strong>{{ $tag->name }}</strong>
            <small>View and edit a tag</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ action("AdminController@index") }}"><i class="fa fa-dashboard"></i> Admin Panel</a></li>
            <li><a href="{{ action('Admin\PostController@index') }}"> Posts</a></li>
            <li><a href="{{ action('Admin\CategoryController@index') }}"> Tags</a></li>
            <li class="active">{{ $tag->name }}</li>
        </ol>
    </section>
    <section class="content">
        {!! $message_success !!}
        <div class="row">
            <div class="col-md-4">
                <form action="{{ action('Admin\TagController@update', ['id', $tag->id]) }}" method="POST">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="PUT">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit</h3>
                        </div>
                        <div class="box-body">
                            {!! \AdminHelper::textField("Name *", "name", old("name", $tag->name), $errors) !!}
                            {!! \AdminHelper::textField("Slug", "slug", old("slug", $tag->slug), $errors) !!}
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Edit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-8">
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Posts tagged as {{ $tag->name }}</h3>
                    </div>
                    <div class="box-body no-padding">
                        <table class="table">
                            <tbody>
                            <tr>
                                <th>Name</th>
                                <th>Date Published</th>
                            </tr>
                            @foreach($tag->posts()->get() as $post)
                                <tr>
                                    <td>
                                        <a href="{{ action('Admin\PostController@edit', ['id' => $post->id]) }}">{{ $post->title }}</a>
                                    </td>
                                    <td>{{ $post->published_on }}</td>
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
                    <a href="javascript:void(0)" class="btn btn-outline pull-left" data-dismiss="modal"
                       aria-label="Close">Cancel</a>
                    <a href="javascript:void(0)" id="modal-btn-delete" class="btn btn-outline">Delete</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script>
        $(function () {
            $("#confirm-delete")
                    .on("show.bs.modal", function (e) {
                        var data = $(e.relatedTarget).data();
                        $("#modal-item-name", this).text(data.name);
                        $("#modal-btn-delete", this).data('href', data.href);
                    })
                    .on("click", "#modal-btn-delete", function () {
                        $.ajax({url: $(this).data('href'), method: "DELETE"})
                                .then(function () {
                                    location.reload();
                                });
                    });
        });
    </script>
@endsection