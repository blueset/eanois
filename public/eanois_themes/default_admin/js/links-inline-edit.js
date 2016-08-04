/**
 * Created by blueset on 26/6/16.
 */

$(function () {

    // Animate.css: https://github.com/daneden/animate.css
    $.fn.extend({
        animateCss: function (animationName) {
            var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
            $(this).addClass('animated ' + animationName).one(animationEnd, function () {
                $(this).removeClass('animated ' + animationName);
            });
        }
    });

    $('button[data-action=edit]').click(function () {
        var entry = $(this).parents('.admin-link-entry');
        var id = entry.data('id');
        if (entry.hasClass('admin-link-divider')) {
            var name = $(".admin-link-divider-name", entry).text();
            var index = $(".admin-link-divider-index", entry).text();
            $(".admin-link-divider-name", entry).html('<input class="inline-edit" type="text" name="name" value="' + name + '"/>');
            $(".admin-link-divider-index", entry).html('<input class="inline-edit" type="number" name="sort_index" value="' + index + '"/>');
            $("button", entry).addClass('hidden');
            entry.data("data", {
                id: id,
                name: name,
                index: index
            });
            // $(".admin-link-divider-index", entry).addClass('hidden');
            // $(".admin-link-divider-name", entry).addClass('hidden');
            // $(".pull-right", entry).prepend('<input class="inline-edit" type="number" name="sort_index" value="'+index+'"/>');
            // $(entry).append('<input class="inline-edit" type="text" name="name" value="'+name+'"/>');
            $(".pull-right", entry).append('<button type="button" class="admin-link-inline-edit-btn btn btn-xs btn-success"><i class="fa fa-check"></i></button>');
            $(".pull-right", entry).append('<button type="button" class="admin-link-inline-edit-btn btn btn-xs btn-danger"><i class="fa fa-close"></i></button>');
            $(".pull-right .btn-success", entry).click(function () {
                var entry = $(this).parents('.admin-link-entry');
                var data = entry.data('data');
                var type = entry.data('type');
                var name = $("input.inline-edit[name=name]", entry).val();
                var index = $("input.inline-edit[name=sort_index]", entry).val();
                $.ajax({
                    url: $('#links-inline-edit').data('edit-url').replace("%s", data.id),
                    method: "PUT",
                    data: {
                        id: data.id,
                        name: name,
                        sort_index: index,
                        type: type
                    },
                    success: function () {
                        entry.data('data', {
                            id: data.id,
                            name: name,
                            index: index
                        });
                        $("button.admin-link-inline-edit-btn.btn-danger", entry).trigger('click');
                    },
                    error: function () {
                        entry.animateCss("shake");
                    }
                });
            });
            $(".pull-right .btn-danger", entry).click(function () {
                var entry = $(this).parents('.admin-link-entry');
                var data = entry.data('data');
                $(".admin-link-inline-edit-btn", entry).remove();
                $("button", entry).removeClass("hidden");
                $(".admin-link-divider-name", entry).text(data.name);
                $(".admin-link-divider-index", entry).text(data.index);
            });
        } else if (entry.hasClass('admin-link-item')) {
            var name = $(".admin-link-item-title a", entry).text();
            var index = $(".admin-link-item-index", entry).text();
            var url = $(".admin-link-item-title a", entry).attr('href');
            var desc = $(".admin-link-item-desc", entry).text();
            $("button", entry).addClass('hidden');
            $(".admin-link-item-title", entry).html('<input class="inline-edit admin-link-item-name" type="text" name="name" value="' + name + '"/> <span class="admin-link-item-url">(<input class="inline-edit" type="url" name="url" value="' + url + '"/>)</span>');
            $(".admin-link-item-index", entry).html('<input class="inline-edit" type="number" name="sort_index" value="' + index + '"/>');
            $(".admin-link-item-desc", entry).html('<input class="inline-edit" type="text" name="desc" value="' + desc + '"/>');
            $(entry).data("data", {
                id: id,
                name: name,
                index: index,
                url: url,
                desc: desc
            });
            $(".admin-link-item-right", entry).append('<button type="button" class="admin-link-inline-edit-btn btn btn-xs btn-success"><i class="fa fa-check"></i></button>');
            $(".admin-link-item-right", entry).append('<button type="button" class="admin-link-inline-edit-btn btn btn-xs btn-danger"><i class="fa fa-close"></i></button>');
            $(".admin-link-item-right .btn-danger", entry).click(function () {
                var entry = $(this).parents('.admin-link-entry');
                var data = entry.data('data');
                $(".admin-link-inline-edit-btn", entry).remove();
                $("button", entry).removeClass("hidden");
                $(".admin-link-item-title", entry).html('<a href="' + data.url + '" class="admin-link-item-name">' + data.name + '</a> <span class="admin-link-item-url">(' + data.url + ')</span>');
                $(".admin-link-item-index", entry).text(data.index);
                $(".admin-link-item-desc", entry).html(data.desc);
            });
            $(".admin-link-item-right .btn-success", entry).click(function () {
                var entry = $(this).parents('.admin-link-entry');
                var data = entry.data('data');
                var type = entry.data('type');
                var name = $("input.inline-edit[name=name]", entry).val();
                var index = $("input.inline-edit[name=sort_index]", entry).val();
                var desc = $("input.inline-edit[name=desc]", entry).val();
                var url = $("input.inline-edit[name=url]", entry).val();
                $.ajax({
                    url: $('#links-inline-edit').data('edit-url').replace("%s", data.id),
                    method: "PUT",
                    data: {
                        id: data.id,
                        name: name,
                        sort_index: index,
                        desc: desc,
                        url: url,
                        type: type
                    },
                    success: function () {
                        entry.data('data', {
                            id: data.id,
                            name: name,
                            index: index,
                            desc: desc,
                            url: url
                        });
                        $("button.admin-link-inline-edit-btn.btn-danger", entry).trigger('click');
                    },
                    error: function () {
                        entry.animateCss("shake");
                    }
                });
            });
        }
    });
});