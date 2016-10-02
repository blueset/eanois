@extends('layouts.panel')

@section('title', $post->title.' - Post - ')

@section('css')
    <link rel="stylesheet" href="{{ asset(Theme::url('plugins/select2/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(Theme::url('css/sortable-app.css')) }}">
    <link rel="stylesheet" href="{{ asset(Theme::url('css/animate.css')) }}">
    @parent
@endsection

@section('content')
    <section class="content-header">
        <h1>Edit post <small>Edit an existing post</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ action("AdminController@index") }}"><i class="fa fa-dashboard"></i> Admin Panel</a></li>
            <li><a href="{{ action('Admin\PostController@index') }}"> Posts</a></li>
            <li class="active">Edit a post</li>
        </ol>
    </section>
    <form action="{{ action('Admin\PostController@update', $post->id) }}" method="post">
    <input type="hidden" name="_method" value="PUT">
    {!! csrf_field() !!}
    <section class="content">
        <div class="row">
            <div class="col-md-8">
                {!! AdminHelper::textField_class("Title", "title", "input-lg", old("title", $post->title), $errors) !!}
                <div class="slug-info{!! $errors->has("slug") ? ' has-error' : '' !!}">
                    <div id="slug-edit">
                        Slug: <span class="form-inline"><input type="text" id="slug" name="slug" value="{{ old("slug", $post->slug) }}" class="form-control"></span> <button type="button" id="btn-slug-reset" class="btn btn-default btn-sm">Use default</button>
                        {!! $errors->has("slug") ? "<span class=\"help-block\"><strong>" . $errors->first("slug") . "</strong></span>" : "" !!}
                    </div>
                </div>
                <div class="form-group{!! $errors->has("desc") ? ' has-error' : '' !!}">
                    <button type="button" class="btn btn-primary btn-xs pull-right"
                            data-toggle="modal" data-target="#mediaModal">
                        <i class="fa fa-photo"></i> Add Media
                    </button>
                    <label>Description</label>
                    {!! $errors->has("desc") ? "<span class=\"help-block\"><strong>" . $errors->first("desc") . "</strong></span>" : "" !!}
                    <textarea class="form-control" rows="10" name="desc" id="desc" data-editor="markdown">{!! old("desc", $post->desc) !!}</textarea>
                </div>
                <div class="form-group{!! $errors->has("body") ? ' has-error' : '' !!}">
                    <button type="button" class="btn btn-primary btn-xs pull-right"
                            data-toggle="modal" data-target="#mediaModal">
                        <i class="fa fa-photo"></i> Add Media
                    </button>
                    <label>Body text</label>
                    {!! $errors->has("body") ? "<span class=\"help-block\"><strong>" . $errors->first("body") . "</strong></span>" : "" !!}
                    <textarea class="form-control" rows="20" name="body" id="body" data-editor="markdown">{!! old("body", $post->body) !!}</textarea>
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
                                <?php $postlink_data = old("postlink", $post->links()->orderBy('order')->get()) ?>
                                @foreach ($postlink_data as $order => $link)
                                <tr class="postlink-item">
                                    <td><span class="btn"><i class="fa fa-bars"></i></span></td>
                                    <td><input type="text" name="postlink[{{ $order }}][name]" class="form-control postlink-name" value="{{ $link['name'] }}"></td>
                                    <td><input type="text" name="postlink[{{ $order }}][url]" class="form-control postlink-link" value="{{ $link['url'] }}"></td>
                                    <td><button type="button" class="btn btn-danger postlink-btn-remove"><i class="fa fa-remove"></i></button></td>
                                </tr>
                                @endforeach
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
                            <?php $postmeta_data = old("postmeta", $post->meta()->get()) ?>
                            @foreach ($postmeta_data as $id => $meta)
                            <tr class="postmeta-item">
                                <td><input type="text" name="postmeta[{{ $id }}][key]" class="form-control postmeta-key" value="{{ $meta['key'] }}"></td>
                                <td><input type="text" name="postmeta[{{ $id }}][value]" class="form-control postmeta-value" value="{{ $meta['value'] }}"></td>
                                <td><a href="javascript:void(0)" class="btn btn-danger postmeta-btn-remove"><i class="fa fa-remove"></i></a></td>
                            </tr>
                            @endforeach
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
                        <?php $published_on_val = old("published_on", $post->published_on); ?>
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
                        <input type="submit" class="btn btn-primary pull-right" value="Edit"/>
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
                                        <option value="{{ $cat->id }}" {{ $cat->id == old('category', $post->category) ? "selected" : "" }}>{{ $cat->name }}</option>
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
                            <input type="hidden" name="image" id="input-featured-image" value="{{ old('image', $post->image) }}">
                            <div id="featured-image" class="hidden">
                                <picture>
                                    <img src="{{-- route("AdminImageControllerShowWidthHeight", [$i->slug, 200, 135]) --}}" alt="Featured Image">
                                </picture>
                                <button type="button" class="btn btn-link btn-block" id="admin-post-remove-img-btn">Remove featured image</button>
                            </div>
                            <button type="button" class="btn btn-default btn-block" id="admin-post-set-featured-img-btn"
                                    data-toggle="modal" data-target="#mediaModal">
                                Set feature Image
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
                <iframe style="width: 100%" data-src="{{ action('AdminController@mediaIframe') }}" frameborder="0"></iframe>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="{{ asset(Theme::url('js/ace/ace.js')) }}"></script>
    <script src="{{ asset(Theme::url('plugins/input-mask/jquery.inputmask.js')) }}"></script>
    <script src="{{ asset(Theme::url('plugins/input-mask/jquery.inputmask.date.extensions.js')) }}"></script>
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
                editor.getSession().setUseWrapMode(true);
                editor.getSession().setValue(textarea.val());
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
                $("#tags").val([
                    @foreach(old("tags", $post->tags()->get()) as $t)
                            "{{ $t['slug'] or $t }}",
                    @endforeach
                ]).trigger('change');
            });

            // Post Links
            function renumberPostLinks(table) {
                table.children().each(function (index) {
                    $(this).find(".postlink-name").attr("name", "postlink[" + index + "][name]");
                    $(this).find(".postlink-link").attr("name", "postlink[" + index + "][url]");
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
                        '<td><input type="text" name="postlink[][name]" class="form-control postlink-name"></td>' +
                        '<td><input type="text" name="postlink[][url]" class="form-control postlink-link"></td>' +
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
                        '<td><input type="text" name="postmeta['+Date.now()+'][key]" class="form-control postmeta-key"></td>' +
                        '<td><input type="text" name="postmeta['+Date.now()+'][value]" class="form-control postmeta-value"></td>' +
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
            var autoSlug = false;
            var updateSlug = function () {
                if (autoSlug) {
                    $.ajax({
                        url: "{{ action("APIController@getSlug") }}",
                        method: "post",
                        data: { msg: $("#input-title").val(), module: {!! json_encode(\App\Post::class) !!}, id: {{ $post->id }} }
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
                    var str = "![" + name + '](image:' + slug + ')';
                    ace.edit($("#desc_ace")[0]).insert(str);
                } else if (dest == "feat") {
                    updateFeaturedImage(slug);
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
                    $("#featured-image").find("img").attr("src",
                            "{{ route("AdminImageControllerShowWidth", ['s1ug', 200]) }}".replace("s1ug", slug)
                    );
                }
            }

            updateFeaturedImage($("#input-featured-image").val());
        });
    </script>
@endsection