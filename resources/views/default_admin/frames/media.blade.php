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
                <ul class="mailbox-attachments clearfix" id="media-tab-images-ul">
                </ul>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left media-tab-image-pagination-btn" id="media-tab-image-prev">Prev</button>
                    <button type="button" class="btn btn-default pull-right media-tab-image-pagination-btn" id="media-tab-image-next">Next</button>
                </div>
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
                    var group = $("<div>", {"class": "btn-group-vertical"});
                    var paras = {!! $paras !!};
                    for (var k in paras){
                        group = group.append($("<button>", {
                                "class": ["btn btn-xs btn-default"],
                                "onclick": "add('"+slug+"', '"+name+"', '" + k + "')"
                            }).text(paras[k]));
                    }
                    return group;
                }
            };


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

            loadImagePage = function (page) {
                if (typeof(page) == "undefined") page = "{{ action('Admin\ImageController@index') }}";
                $.ajax({
                    url: page,
                    dataType: "json",
                }).done(function (data) {
                    $("#media-tab-image-next")
                            .attr("disabled", data['next_page_url'] === null)
                            .data("href", data['next_page_url']);
                    $("#media-tab-image-prev")
                            .attr("disabled", data['prev_page_url'] === null)
                            .data("href", data['prev_page_url']);

                    $(".media-tab-image-pagination-btn").click(function (){
                        if ($(this).data("href")) {
                            loadImagePage($(this).data("href"));
                        }
                    });



                    $("#media-tab-images-ul").html("");

                    data['data'].forEach(function (val, key, arr) {
                        $("<li>", {"class": "image-item"})
                                .data("slug", val['slug'])
                                .append($("<span>", {"class": "mailbox-attachment-icon has-img"})
                                        .append($(val['backend_media_preview_html'])))
                                .append($("<div>", {"class": "mailbox-attachment-info image-item-name"})
                                        .append("<a>", {
                                            "href": "javascript:void(0)",
                                            "class": "mailbox-attachment-name"
                                        }).text(val['title']))
                                .appendTo("#media-tab-images-ul");
                    });

                    $(".image-item").popover(popoverPara);

                });
            };


            loadImagePage();

        });
    </script>
@endsection