@extends('layouts.base')

@section('body')
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab">Upload Image</a></li>
            <li><a href="#tab_2" data-toggle="tab">Choose Image</a></li>
            <li><a href="#tab_3" data-toggle="tab">Choose File</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <form action="{{ action('Admin\ImageController@store') }}" method="post" enctype="multipart/form-data" id="image-upload">
                    {{ csrf_field() }}
                    <div class="fallback">
                        <input type="file" name="file" multiple>
                    </div>
                    <div class="dz-default upload-trigger" style="display: flex; position: relative;">
                        <span>Click or drop here to upload.</span>
                    </div>
                    <div class="mailbox-attachments clearfix dropzone-previews" id="upload-previews">

                    </div>
                    <div id="template">
                        <li class="image-item">
                            <span class="mailbox-attachment-icon">
                                <i class="fa fa-file-o"></i>
                            </span>
                            <div class="mailbox-attachment-info">
                                <div class="progress progress-xxs active dz-progress" data-dz-uploadprogress>
                                    <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"></div>
                                </div>
                                <span class="mailbox-attachment-name" data-dz-name></span>
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


            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_2">
                <ul class="mailbox-attachments clearfix">
                    @foreach($images as $i)
                        <li class="image-item" data-slug="{{ $i->slug }}">
                            <span class="mailbox-attachment-icon has-img">
                                {!! $i->pictureElement()->mode("WidthHeight")->width(200)->height(135) !!}
                            </span>
                            <div class="mailbox-attachment-info image-item-name">
                                <a href="javascript:void(0)" class="mailbox-attachment-name">{{ $i->title }}</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_3">
                To be implemented.
            </div>
            <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
    </div>
    <!-- nav-tabs-custom -->
@endsection

@section('js')
    @parent
    <script src="{{ asset(Theme::url('js/dropzone.js')) }}"></script>
    <script>
        $(function () {
            var win = window.dialogArguments || opener || parent || top;

            var popoverPara = {
                html: true,
                placement: "bottom",
                content: function () {
                    var slug = $(this).data('slug');
                    var name = $(this).find('.mailbox-attachment-name').text();
                    return '<div class="btn-group-vertical">' +
                            '<button type="button" class="btn btn-xs btn-default" onclick="add(\''+slug+ '\', \''+name+'\', \'desc\')">Add to description</button>' +
                            '<button type="button" class="btn btn-xs btn-default" onclick="add(\''+slug+ '\', \''+name+'\', \'body\')">Add to body</button>' +
                            '<button type="button" class="btn btn-xs btn-default" onclick="add(\''+slug+ '\', \''+name+'\', \'feat\')">Set as featured image</button>' +
                            '</div>'
                }
            };

            $(".image-item").popover(popoverPara);

            window.add = function (slug, name, dest) {
                win.insertImage(slug, name, dest);
            }

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
                $(file.previewElement).find(".progress-bar").css('width', progress+"%").attr('aria-valuenow', progress);
            }).on('sending', function(file){
                $(file.previewElement).find(".progress-bar").css('display', 'block');
            }).on('complete', function(file){
                $(file.previewElement).find(".progress-bar").css('display', 'none');
            }).on('success', function(file){
                $(file.previewElement).find(".btn-success").removeClass('hidden');
                var data = JSON.parse(file.xhr.response);
                $(file.previewElement).data("slug", data.slug).popover(popoverPara);
            }).on('error', function(file, errorMessage){
                $(file.previewElement).find(".btn-danger").removeClass('hidden');
                $(file.previewElement).find(".dz-error-msg").text(errorMessage['file']);
            }).on('thumbnail', function(file, dataurl){
                var attachmentIcon = $(file.previewElement).find(".mailbox-attachment-icon").addClass("has-img");
                attachmentIcon.find("i.fa").remove();
                attachmentIcon.append('<img src="' + dataurl + '" title="thumbnail" />');
            });


            $("#template").remove();

        });
    </script>
@endsection