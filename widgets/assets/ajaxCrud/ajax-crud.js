
var ajaxCrud = (function($) {
    var pub = {
        spinner: '<i class="fa fa-spinner fa-spin"></i>',
        load: function(container, url, append) {
            append = append || false;
            if (append) {
                $(container).append($(pub.spinner).addClass('ajax-crud-spinner'));
            } else {
                $(container).html($(pub.spinner).addClass('ajax-crud-spinner'));
            }
            $.ajax({
                type: 'get',
                url: url,
                dataType: 'html',
                success: function(html) {
                    if (append) {
                        $(container).append(html);
                    } else {
                        $(container).html(html);
                    }
                    $(container).find('.ajax-crud-spinner').remove();
                }
            });
        },
        init: function() {
            // index
            var index = $('.ajax-crud-type-index');
            index.on('click', '.js-btn-ajax-crud-create', function() {
                var container = $(this).closest('.ajax-crud-container');
                pub.load(container.data('container-create'), $(this).attr('href'));
                return false;
            });
            index.on('click', '.js-btn-ajax-crud-update', function() {
                var container = $(this).closest('.ajax-crud-container');
                pub.load(container.data('container-update'), $(this).attr('href'), false);
                return false;
            });
        }
    };

    return pub;
})(jQuery);