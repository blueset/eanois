@extends('layouts.panel')

@section('title', 'Pages - ')

@section('css')
    @parent

@endsection

@section('content')
    <section class="content-header">
        <h1>Pages <small>List of pages</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ action("AdminController@index") }}"></a><i class="fa fa-dashboard"></i> Admin Panel</li>
            <li class="active">Page list</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default">
            <div class="box-header">
                <h3 class="box-title">Pages</h3>
                <div class="box-tools">
                    @include('paginator.default', ['paginator' => $pages, 'class' => 'pagination-sm no-margin pull-right'])
                </div>
            </div>
            <div class="box-body no-padding">
                <table class="table">
                    <tbody>
                    <tr>
                        <th>Title</th>
                        <th>Published on</th>
                        <th class="table-action"></th>
                    </tr>
                    @foreach($pages as $p)
                        <tr>
                            <td><a href="{{ action('Admin\PageController@edit', ['id' => $p->id]) }}">{{ $p->title }}</a></td>
                            <td>{{ $p->published_on }}</td>
                            <td>
                                <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#confirm-delete" data-name="{{ $p->name }} ({{ $p->slug }})" data-href="{{ action('Admin\PageController@destroy', ['id' => $p->id]) }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
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
    <script>
        $(function (){
            $("#confirm-delete")
                    .on("show.bs.modal", function (e) {
                        var data = $(e.relatedTarget).data();
                        $("#modal-item-name", this).text(data.name);
                        $("#modal-btn-delete", this).data('href', data.href);
                    })
                    .on("click", "#modal-btn-delete", function () {
                        var href = $(this).data('href');

                        $.ajax({url: $(this).data('href'), method: "DELETE"})
                                    .then(function(){location.reload();});

                    });
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
            $("input#all-posts").on('ifToggled', function (e) {
                var main = $(this).is(":checked");
                $(".select-post").each(function () {
                    if (main) {
                        $(this).iCheck('check');
                    } else {
                        $(this).iCheck('uncheck');
                    }
                });
            });
            $(".select-post").on('ifToggled', function (e) {
                var hasChecked = false;
                var hasUnchecked = false;
                $(".select-post").each(function () {
                    if ($(this).is(":checked")) {
                        hasChecked = true;
                    } else {
                        hasUnchecked = true;
                    }
                });
                if (hasChecked && hasUnchecked) {
                    $("input#all-posts").iCheck('indeterminate');
                } else if (hasChecked) {
                    $("input#all-posts").iCheck('check');
                    $("input#all-posts").iCheck('determinate');
                } else {
                    $("input#all-posts").iCheck('uncheck');
                    $("input#all-posts").iCheck('determinate');
                }
            });
        });

    </script>
@endsection