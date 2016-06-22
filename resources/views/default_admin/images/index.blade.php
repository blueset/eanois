@extends('layouts.panel')

@section('title', 'Images - ')

@section('css')
    @parent
    {{--<link rel="stylesheet" href="{{ asset(Theme::url('css/dropzone.css')) }}">--}}
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
                    <button type="button" class="btn btn-small btn-success upload-trigger">
                        <i class="fa fa-upload"></i> Upload new picture
                    </button>
                </div>
            </div>
            <div class="box-body" id="images-list">
                <form action="{{ action('Admin\ImageController@store') }}" method="post" enctype="multipart/form-data" id="image-upload">
                    {{ csrf_field() }}
                    <div class="fallback">
                        <input type="file" name="file" multiple>
                    </div>
                    <div class="mailbox-attachments clearfix dropzone-previews" id="upload-previews">

                    </div>
                    <div class="dz-default">
                        Drop here to upload
                    </div>
                    <div id="template">
                        <li>
                            <span class="mailbox-attachment-icon">
                                <i class="fa fa-file-o"></i>
                            </span>
                            <div class="mailbox-attachment-info">
                                <div class="progress progress-xxs active dz-progress" data-dz-uploadprogress>
                                    <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"></div>
                                </div>
                                <span data-dz-name></span>
                            <span class="mailbox-attachment-size">
                                <span data-dz-size></span>
                                <span class="text-danger dz-error-msg"></span>
                                <button class="btn btn-xs btn-success pull-right hidden" disabled type="button">
                                    <i class="fa fa-fw fa-check"></i>
                                </button>
                                <button class="btn btn-xs btn-danger pull-right hidden" disabled type="button">
                                    <i class="fa fa-fw fa-exclamation"></i>
                                </button>
                            </span>
                            </div>
                        </li>
                    </div>

                </form>
                @if(count($images) == 0)
                <div class="images-empty upload-trigger">
                    <span>No file found. <br>
                    Click or drop here to upload.</span>
                </div>
                @else
                <ul class="mailbox-attachments clearfix">
                    @foreach($images as $i)
                    <li>
                        <span class="mailbox-attachment-icon has-img">
                            <picture>
                                <source srcset="{{ route("AdminImageControllerShowWidthHeightExt", [$i->slug, 200, 135, 'webp']) }}" type="image/webp">
                                <img src="{{ route("AdminImageControllerShowWidthHeight", [$i->slug, 200, 135]) }}" alt="{{ $i->title }}">
                            </picture>
                        </span>
                        <div class="mailbox-attachment-info">
                            <a href="{{ route("AdminImageControllerShowExt", [$i->slug, $i->getExt()]) }}" class="mailbox-attachment-name">{{ $i->title }}</a>
                            <span class="mailbox-attachment-size">
                                Slug: {{ $i->slug }}
                                <button type="button" class="btn btn-danger btn-xs pull-right" data-toggle="modal" data-target="#confirm-delete" data-name="{{ $i->title }} ({{ $i->slug }})" data-href="{{ action('Admin\ImageController@destroy', ['id' => $i->id]) }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </span>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif
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
                    <picture id="modal-delete-item-img"></picture>
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
            var dz = new Dropzone("#image-upload", {
                url: "{{ action('Admin\ImageController@store') }}",
                parallelUploads: 2,
                previewTemplate: $('#template').html(),
                clickable: ".upload-trigger",
                thumbnailWidth: 198,
                thumbnailHeight: 120,
                previewsContainer: "#upload-previews"
            });

            dz.on("uploadprogress", function(file, progress, bytesent){
                console.log(this);
                console.log(file);
                console.log(progress);
                $(file.previewElement).find(".progress-bar").css('width', progress+"%").attr('aria-valuenow', progress);
            }).on('sending', function(file){
                $(file.previewElement).find(".progress-bar").css('display', 'block');
            }).on('complete', function(file){
                $(file.previewElement).find(".progress-bar").css('display', 'none');
            }).on('success', function(file){
                $(file.previewElement).find(".btn-success").removeClass('hidden');
            }).on('error', function(file, errorMessage){
                $(file.previewElement).find(".btn-danger").removeClass('hidden');
                $(file.previewElement).find(".dz-error-msg").text(errorMessage['file']);
            }).on('thumbnail', function(file, dataurl){
                var attachmentIcon = $(file.previewElement).find(".mailbox-attachment-icon").addClass("has-img");
                attachmentIcon.find("i.fa").remove();
                attachmentIcon.append('<img src="' + dataurl + '" title="thumbnail" />');
            });

            $("#template").remove();

            $('#images-list').on('dragenter', function(){
                $(".dz-default").css("display", "flex");
            });
            $('.dz-default').on('dragleave', function(){
                $(".dz-default").css("display", "none");
            });
            $('.dz-default').on('drop', function(){
                $(".dz-default").css("display", "none");
            });

        });
    </script>
@endsection