@extends('layouts.panel')

@section('title', 'Images - ')

@section('css')
    @parent

@endsection

@section('content')
    <section class="content-header">
        <h1>Images <small>List of images</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ action("AdminController@index") }}"></a><i class="fa fa-dashboard"></i> Admin Panel</li>
            <li class="active">Images</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default">
            <div class="box-header">
                <h3 class="box-title">Images</h3>
                <div class="box-tools">
                    <button type="button" class="btn btn-small btn-success">
                        <i class="fa fa-upload"></i> Upload new picture
                    </button>
                </div>
            </div>
            <div class="box-body">
                <form action="{{ action('Admin\ImageController@store') }}" method="post" enctype="multipart/form-data" class="dropzone" id="image-upload">
                    {{ csrf_field() }}
                    <div class="fallback">
                        <input type="file" name="file[]" multiple>
                    </div>
                </form>

            </div>
            <div class="box-footer">
                {{--@include('paginator.default', ['paginator' => $posts, 'class' => 'pagination-sm no-margin pull-right'])--}}
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
    <script src="{{ asset(Theme::url('plugins/iCheck/icheck.min.js')) }}"></script>
    <script src="{{ asset(Theme::url('js/dropzone.js')) }}"></script>
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


            // Dropzone
            var dz = $("#image-upload").dropzone({
                url: "{{ action('Admin\ImageController@store') }}",
                parallelUploads: 2,

            });
        });
    </script>
@endsection