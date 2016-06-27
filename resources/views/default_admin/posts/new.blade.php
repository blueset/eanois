@extends('layouts.panel')

@section('title', 'New post - ')

@section('css')
    <link rel="stylesheet" href="{{ asset(Theme::url('plugins/select2/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(Theme::url('css/sortable-app.css')) }}">
    <link rel="stylesheet" href="{{ asset(Theme::url('css/animate.css')) }}">
    @parent
@endsection

@section('content')
    <section class="content-header">
        <h1>New post <small>Create a new post</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ action("AdminController@index") }}"></a><i class="fa fa-dashboard"></i> Admin Panel</li>
            <li><a href="{{ action('Admin\PostController@index') }}"> Posts</a></li>
            <li class="active">New post</li>
        </ol>
    </section>
    <form action="{{ action('Admin\PostController@store') }}" method="post">
    {!! csrf_field() !!}
    <section class="content">
        <div class="row">
            <div class="col-md-8">
                {!! AdminHelper::textField_class("Title", "title", "input-lg", old("title"), $errors) !!}
                <div class="slug-info">
                    <div id="slug-edit">
                        Slug: <span class="form-inline"><input type="text" id="slug" name="slug" value="{{ old("slug") }}" class="form-control"></span> <button type="button" id="btn-slug-reset" class="btn btn-default btn-sm">Use default</button>
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" rows="10" name="desc" id="desc" data-editor="markdown">{!! old("desc") !!}</textarea>
                </div>
                <div class="form-group">
                    <label>Body text</label>
                    <textarea class="form-control" rows="20" name="body" id="body" data-editor="markdown">{!! old("body") !!}</textarea>
                </div>
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Post Links</h3>
                        <div class="box-tools">
                            <button type="button" class="btn btn-success" id="postlink-add">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body no-padding">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="table-action"></th>
                                <th>Name</th>
                                <th>Link</th>
                                <th class="table-action">Action</th>
                            </tr>
                            </thead>
                            <tbody id="postlink-list">
                                <tr class="postlink-item">
                                    <td><span class="btn"><i class="fa fa-bars"></i></span></td>
                                    <td><input type="text" name="postlink_name[0]" class="form-control postlink-name"></td>
                                    <td><input type="text" name="postlink_link[0]" class="form-control postlink-link"></td>
                                    <td><button type="button" class="btn btn-danger postlink-btn-remove"><i class="fa fa-remove"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Additional Parameters</h3>
                        <div class="box-tools">
                            <button type="button" class="btn btn-success" id="postmeta-add">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body no-padding">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Key</th>
                                <th>Value</th>
                                <th class="table-action">Action</th>
                            </tr>
                            </thead>
                            <tbody id="postmeta-list">
                            <tr class="postmeta-item">
                                <td><input type="text" name="postmeta_key[]" class="form-control postmeta-key"></td>
                                <td><input type="text" name="postmeta_value[]" class="form-control postmeta-value"></td>
                                <td><a href="javascript:void(0)" class="btn btn-danger postmeta-btn-remove"><i class="fa fa-remove"></i></a></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Publish</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label>Publish on:</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" name="published_on" id="published_on" placeholder="Immediately">
                            </div>
                        </div>
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
                        <div class="form-group">
                            <label>Category:</label>
                            <div class="input-group" id="cat-list">
                                <select name="category" id="category" class="form-control">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default btn-cat-toggle"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="input-group hidden" id="cat-add">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default btn-cat-toggle"><i class="fa fa-share fa-flip-horizontal"></i></button>
                                </div>
                                <input type="text" class="form-control" id="cat-new-name">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-success" id="btn-cat-add"><i class="fa fa-check"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Tags:</label>
                            <select name="tags[]" id="tags" multiple class="form-control" style="width: 100%;">
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Featured Image:</label>
                            <div id="featured-image"></div>
                            <button type="button" class="btn btn-default" id="admin-post-set-featured-img-btn"
                                    data-toggle="modal" data-target="#mediaModal">Set Image
                            </button>
                        </div>
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
                <iframe class="modal-body" src="{{ action('AdminController@mediaIframe') }}" frameborder="0"></iframe>
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

        $(function () {
            // Ace editor
            $('textarea[data-editor]').each(function () {
                var textarea = $(this);
                var mode = textarea.data('editor');
                var editDiv = $('<div>', {
                    // position: 'absolute',
                    height: textarea.height(),
                    'class': textarea.attr('class')
                }).insertBefore(textarea);
                textarea.css('display', 'none');
                var editor = ace.edit(editDiv[0]);
                // editor.renderer.setShowGutter(false);
                editor.setOption("wrap", 80);
                editor.getSession().setValue(textarea.val());
                editor.getSession().setMode("ace/mode/" + mode);
                editor.setOptions({fontFamily: "Input Mono Narrow", fontSize: 14});
                // editor.setTheme("ace/theme/idle_fingers");

                // copy back to textarea on form submit...
                textarea.closest('form').submit(function () {
                    textarea.val(editor.getSession().getValue());
                })
            });

            // Datetime input mask
            $("#published_on").inputmask('y-m-d h:s:s');


            // Select2 Tags
            $.ajax({
                url: "{{ action('Admin\TagController@index') }}",
                method: "GET"
            }).done(function (data) {
                var cats = [];
                $.each(data, function (key, val) {
                    cats.push({id: val.slug, text: val.name});
                });
                $("#tags").select2({
                    tags: true,
                    data: cats
                });
            });



            // Post Links
            function renumberPostLinks(table) {
                table.children().each(function (index) {
                    $(this).find(".postlink-name").attr("name", "postlink_name[" + index + "]");
                    $(this).find(".postlink-link").attr("name", "postlink_link[" + index + "]");
                });
            }

            Sortable.create(document.getElementById('postlink-list'), {
                ghostClass: "sortable-ghost",
                draggable: ".postlink-item",
                handle: ".btn",
                onEnd: function (e) {
                    renumberPostLinks($(e.srcElement));
                },
            });

            $("#postlink-list").on("click", ".postlink-btn-remove", function () {
                $.when($(this).parents(".postlink-item").remove()).then(renumberPostLinks($("#postlink-list")));
            });

            $("#postlink-add").click(function () {
                $('#postlink-list').append(
                        '<tr class="postlink-item">' +
                        '<td><span class="btn"><i class="fa fa-bars"></i></span></td>' +
                        '<td><input type="text" name="postlink_name[]" class="form-control postlink-name"></td>' +
                        '<td><input type="text" name="postlink_link[]" class="form-control postlink-link"></td>' +
                        '<td><a href="javascript:void(0)" class="btn btn-danger postlink-btn-remove"><i class="fa fa-remove"></i></a></td>' +
                        '</tr>'
                );
                renumberPostLinks($("#postlink-list"));
            });

            // Post Meta
            $("#postmeta-list").on("click", ".postmeta-btn-remove", function () {
                $(this).parents(".postmeta-item").remove();
            });

            $("#postmeta-add").click(function () {
                $('#postmeta-list').append(
                        '<tr class="postmeta-item">' +
                        '<td><input type="text" name="postmeta_key[]" class="form-control postmeta-key"></td>' +
                        '<td><input type="text" name="postmeta_value[]" class="form-control postmeta-value"></td>' +
                        '<td><a href="javascript:void(0)" class="btn btn-danger postmeta-btn-remove"><i class="fa fa-remove"></i></a></td>' +
                        '</tr>'
                );
            });

            // Retrieve Categories
            function retrieveCategories() {
                $.ajax({
                    url: "{{ action('Admin\CategoryController@index') }}",
                    method: "GET"
                }).done(function (obj) {
                    var sel = $("select[name=category]").empty();
                    $.each(obj, function (key, val) {
                        sel.append('<option value="' + val.id + '">' + val.name + '</option>');
                    });
                });
            }

            // Add categories
            $(".btn-cat-toggle").click(function(){
                $("#cat-list").toggleClass("hidden");
                $("#cat-add").toggleClass("hidden");
            });

            $("#btn-cat-add").click(function () {
                var catName = $("#cat-new-name").val();
                if (catName == ""){
                    $("#cat-new-name").animateCss("shake");
                    return;
                }
                $.ajax({
                    url: "{{ action('Admin\CategoryController@store') }}",
                    method: "POST",
                    data: {"name": catName}
                }).done(function () {
                    retrieveCategories();
                    $("#category").find("option:last").attr("selected", "selected");
                    $("#cat-list").toggleClass("hidden");
                    $("#cat-add").toggleClass("hidden");
                }).fail(function () {
                    $("#cat-new-name").effect("shake");
                });
            });


            // Post Slugs
            var autoSlug = true;
            var updateSlug = function () {
                if (autoSlug) {
                    $.ajax({
                        url: "{{ action("APIController@getSlug") }}",
                        method: "get",
                        data: { msg: $("#input-title").val() }
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

            window.insertImage = function (slug) {
                console.log(slug);
            }
        });
    </script>
@endsection