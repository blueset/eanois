@extends('layouts.panel')

@section('title', 'Settings - ')

@section('content')
    <section class="content-header">
        <h1>Settings <small>Eanois Content Management System</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ action("AdminController@index") }}"><i class="fa fa-dashboard"></i> Admin Panel</a></li>
            <li class="active">Settings</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-lg-8">
                {!! $message_success !!}
                <form action="{{ action("AdminController@putSettings") }}" method="POST" role="form">
                    <input type="hidden" name="_method" value="PUT">
                    {!! csrf_field() !!}
                    <div class="box">
                        <div class="box-body">
                            {!! AdminHelper::textField("Site name", "site_name", array_get($config, 'site_name', '')) !!}
                            {!! AdminHelper::form_group()
                                ->title("Site description")
                                ->field("site_desc")
                                ->value(array_get($config, 'site_desc', ''))
                                ->desc("Description of the site, usually used for SEO.") !!}
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                Feeds
                            </h3>
                        </div>
                        <div class="box-body">
                            {!! AdminHelper::form_group()
                                ->title("Feed URL")
                                ->field("feed_url")
                                ->value(array_get($config, 'feed_url', ''))
                                ->desc("URLs of feeds to be included in the “Last Updates” section. Leave blank to disable. Separate URLs with a space.") !!}
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                SEO & Bots
                            </h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="feed_url">Site Logo</label>
                                <input type="hidden" name="site_logo" id="input-site-logo" val="{{ old('site_logo', array_get($config, 'site_logo', '')) }}">
                                <div>
                                    <button type="button" class="btn btn-primary"
                                            data-toggle="modal" data-target="#mediaModal">
                                        <i class="fa fa-photo"></i> Choose image
                                    </button>
                                    <div id="site-logo">
                                        <picture>
                                            <img src="{{ route("AdminImageControllerShowWidth", [old('site_logo', array_get($config, 'site_logo', '')), 200]) }}">
                                        </picture>
                                    </div>
                                </div>
                                <p class="help-block">Site logo used for bots including search engine spiders.</p>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </section>

    <div class="modal fade" id="mediaModal" tabindex="-1" role="dialog" aria-labelledby="mediaModalTitle">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="mediaModalTitle">Add media</h4>
                </div>
                <iframe style="width: 100%" data-src="{{ action('AdminController@mediaIframe') }}?type=single" frameborder="0"></iframe>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script>
        $(function () {
            $("#mediaModal").on('show.bs.modal', function () {
                if (! $("iframe", this).attr("src")){
                    $("iframe", this).attr("src", function(){return $(this).data('src')});
                }
                $("iframe", this).height($(window).height() - 150);
            });

            window.insertImage = function (slug, name, dest) {
                $("#mediaModal").modal('hide');
                if (dest == "select") {
                    $("#input-site-logo").val(slug);
                    $("#site-logo").find("img").attr("src",
                        "{{ route("AdminImageControllerShowWidth", ['s1ug', 200]) }}".replace("s1ug", slug)
                    );
                }
            };
        });
    </script>
@endsection