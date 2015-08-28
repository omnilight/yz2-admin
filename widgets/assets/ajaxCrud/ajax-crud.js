var ajaxCrud = (function ($) {
    var pub = {
        spinner: '<i class="fa fa-spinner fa-spin"></i>',
        itemWrapper: '{content}',
        createItemWrapper: '{content}',
        updateItemWrapper: '{content}',
        load: function (container, url, append, wrapper) {
            append = append || false;
            wrapper = wrapper || pub.itemWrapper;
            if (append) {
                $(container).append($(pub.spinner).addClass('ajax-crud-spinner'));
            } else {
                $(container).html($(pub.spinner).addClass('ajax-crud-spinner'));
            }
            $.ajax({
                type: 'get',
                url: url,
                dataType: 'html',
                success: function (html) {
                    var content = wrapper
                        .replace('{content}', html);
                    if (append) {
                        $(container).append(content);
                    } else {
                        $(container).html(content);
                    }
                    $(container).find('.ajax-crud-spinner').remove();
                }
            });
        },
        init: function () {
            var containers = $('.ajax-crud-container');
            containers.on('click', '.js-btn-ajax-crud-create', function () {
                var container = $(this).closest('.ajax-crud-container');
                pub.load(container.data('container-create'), $(this).attr('href'), true, pub.createItemWrapper);
                return false;
            });
            containers.on('click', '.js-btn-ajax-crud-update', function () {
                var container = $(this).closest('.ajax-crud-container');
                pub.load(container.data('container-update'), $(this).attr('href'), false, pub.updateItemWrapper);
                return false;
            });
            containers.on('click', '.js-btn-ajax-crud-cancel', function () {
                var item = $(this).closest('.ajax-crud-item');
                item.remove();
                return false;
            });
        }
    };

    return pub;
})(jQuery);