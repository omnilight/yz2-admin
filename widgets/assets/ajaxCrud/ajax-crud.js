
var ajaxCrud = (function($) {
    var pub = {
        load: function(container, url) {
            $.ajax({
                type: 'get',
                url: url,
                dataType: 'html',
                success: function(html) {
                    $(container).append(html);
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
        }
    };

    return pub;
})(jQuery);