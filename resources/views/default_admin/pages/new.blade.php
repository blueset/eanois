@extends('layouts.panel')

@section('title', 'New page - ')

@section('css')
    <link rel="stylesheet" href="{{ asset(Theme::url('plugins/select2/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(Theme::url('css/sortable-app.css')) }}">
    <link rel="stylesheet" href="{{ asset(Theme::url('css/animate.css')) }}">
    @parent
@endsection

@section('content')
    <section class="content-header">
        <h1>New page <small>Create a new page</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ action("AdminController@index") }}"><i class="fa fa-dashboard"></i> Admin Panel</a></li>
            <li><a href="{{ action('Admin\PageController@index') }}"> Pages</a></li>
            <li class="active">New page</li>
        </ol>
    </section>
    <form action="{{ action('Admin\PageController@store') }}" method="post">
        {!! csrf_field() !!}
        <section class="content">
            <div class="row">
                <div class="col-md-8">
                    {!! AdminHelper::textField_class("Title", "title", "input-lg", old("title"), $errors) !!}
                    <div class="slug-info{!! $errors->has("slug") ? ' has-error' : '' !!}">
                        <div id="slug-edit">
                            Slug: <span class="form-inline"><input type="text" id="slug" name="slug" value="{{ old("slug") }}" class="form-control"></span> <button type="button" id="btn-slug-reset" class="btn btn-default btn-sm">Use default</button>
                            {!! $errors->has("slug") ? "<span class=\"help-block\"><strong>" . $errors->first("slug") . "</strong></span>" : "" !!}
                        </div>
                    </div>
                    <div class="form-group{!! $errors->has("body") ? ' has-error' : '' !!}">
                        <button type="button" class="btn btn-primary btn-xs pull-right"
                                data-toggle="modal" data-target="#mediaModal">
                            <i class="fa fa-photo"></i> Add Media
                        </button>
                        <label>Body</label>
                        {!! $errors->has("body") ? "<span class=\"help-block\"><strong>" . $errors->first("body") . "</strong></span>" : "" !!}
                        <textarea class="form-control" rows="20" name="body" id="body" data-editor="markdown">{!! old("body") !!}</textarea>
                    </div>
                    <div class="form-group{!! $errors->has("data") ? ' has-error' : '' !!}">
                        <label>Data (JSON)</label>
                        {!! $errors->has("data") ? "<span class=\"help-block\"><strong>" . $errors->first("data") . "</strong></span>" : "" !!}
                        <textarea class="form-control" rows="10" name="data" id="data" data-editor="json">{!! old("data") !!}</textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Publish</h3>
                        </div>
                        <div class="box-body">
                            <?php $published_on_val = old("published_on"); ?>
                            {!! \App\Helpers\AdminHelper::form_group()
                                ->errors($errors)
                                ->title("Publish on:")
                                ->field("published_on")
                                ->custom_input(<<<HDC
<div class="input-group date">
    <div class="input-group-addon">
        <i class="fa fa-calendar"></i>
    </div>
    <input type="text" class="form-control pull-right" name="published_on" id="published_on" placeholder="Immediately" value="$published_on_val">
</div>
HDC
) !!}
                        </div>
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary pull-right" value="Publish"/>
                        </div>
                    </div>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Meta</h3>
                        </div>
                        <div class="box-body">
                            {!! \App\Helpers\AdminHelper::form_group()->title("Template")->field("template")->desc("Specify the template used for this page.")->errors($errors) !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>

    <div class="modal fade" id="mediaModal" tabindex="-1" role="dialog" aria-labelledby="mediaModalTitle">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="mediaModalTitle">Add media</h4>
                </div>
                <iframe style="width: 100%" data-src="{{ action('AdminController@mediaIframe') }}" frameborder="0"></iframe>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="{{ asset(Theme::url('js/ace/ace.js')) }}"></script>
    <script src="{{ asset(Theme::url('plugins/input-mask/jquery.inputmask.js')) }}"></script>
    <script src="{{ asset(Theme::url('plugins/input-mask/jquery.inputmask.date.Extensions.js')) }}"></script>
    <script src="{{ asset(Theme::url('plugins/select2/select2.full.min.js')) }}"></script>
    <script src="{{ asset(Theme::url('js/sortable.min.js')) }}"></script>
    <script src="{{ asset(Theme::url('js/jquery-donetyping.js')) }}"></script>
    <script>
        // Animate.css: https://github.com/daneden/animate.css
        $.fn.extend({
            animateCss: function (animationName) {
                var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
                $(this).addClass('animated ' + animationName).one(animationEnd, function () {
                    $(this).removeClass('animated ' + animationName);
                });
            }
        });

        // String.format by fearphage: https://stackoverflow.com/questions/610406/javascript-equivalent-to-printf-string-format
        if (!String.prototype.format) {
            String.prototype.format = function() {
                var args = arguments;
                return this.replace(/{(\d+)}/g, function(match, number) {
                    return typeof args[number] != 'undefined'
                            ? args[number]
                            : match
                            ;
                });
            };
        }



        $(function () {
            // Ace editor
            $('textarea[data-editor]').each(function () {
                var textarea = $(this);
                var mode = textarea.data('editor');
                var editDiv = $('<div>', {
                    height: textarea.height(),
                    'class': textarea.attr('class'),
                    'id': textarea.attr('id') + "_ace"
                }).insertBefore(textarea);
                textarea.css('display', 'none');
                var editor = ace.edit(editDiv[0]);
                editor.setOption("wrap", 80);
                editor.getSession().setValue(textarea.val());
                editor.getSession().setUseWrapMode(true);
                editor.getSession().setMode("ace/mode/" + mode);
                editor.setOptions({fontFamily: "Input Mono Narrow", fontSize: 14});
                editor.on('change', function () {
                    textarea.val(editor.getSession().getValue());
                });

                // copy back to textarea on form submit...
                textarea.closest('form').submit(function () {
                    textarea.val(editor.getSession().getValue());
                })
            });

            // Datetime input mask
            $("#published_on").inputmask('y-m-d h:s:s');

            // Page Slugs
            var autoSlug = true;
            var updateSlug = function () {
                if (autoSlug) {
                    $.ajax({
                        url: "{{ action("APIController@getSlug") }}",
                        method: "post",
                        data: { msg: $("#input-title").val(), module: {!! json_encode(\App\Page::class) !!} }
                    }).done(function (data) {
                        $("#slug").val(data);
                    });
                }
            };
            $("#slug").keyup(function () {
                autoSlug = false;
            });
            $("#btn-slug-reset").click(function () {
                autoSlug = true;
                updateSlug();
            });
            $("#input-title").donetyping(updateSlug);

            $("#mediaModal").on('show.bs.modal', function () {
                if (! $("iframe", this).attr("src")){
                    $("iframe", this).attr("src", function(){return $(this).data('src')});
                }
                $("iframe", this).height($(window).height() - 150);
            });

            window.insertImage = function (slug, name, dest) {
                $("#mediaModal").modal('hide');
                if (dest == "body") {
                    var str = "![" + name + '](image:' + slug + ')';
                    ace.edit($("#body_ace")[0]).insert(str);
                } else if (dest == "desc") {
                    alert("Not available here.");
                } else if (dest == "feat") {
                    alert("Not available here.");
                }
            };

            $("#admin-post-remove-img-btn").click(function () {
                updateFeaturedImage("");
            });

            function updateFeaturedImage(slug) {
                $("#input-featured-image").val(slug);
                if (slug == "") {
                    $("#admin-post-set-featured-img-btn").removeClass("hidden");
                    $("#featured-image").addClass("hidden");
                } else {
                    $("#admin-post-set-featured-img-btn").addClass("hidden");
                    $("#featured-image").removeClass("hidden");
                    $("#featured-image").find("source[type='image/webp']").attr("srcset",
                            "{{ route("AdminImageControllerShowWidthExt", ['s1ug', 200, 'webp']) }}".replace("s1ug", slug)
                    );
                    $("#featured-image").find("img").attr("src",
                            "{{ route("AdminImageControllerShowWidth", ['s1ug', 200]) }}".replace("s1ug", slug)
                    );
                }
            }
        });
    </script>
@endsection