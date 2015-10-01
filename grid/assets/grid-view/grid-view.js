var adminGridView = (function ($) {
    var funcs = {
        bindEvents: function () {
            $('.js-btn-admin-grid-settings').click(function (e) {
                e.preventDefault();

                var $this = $(this);
                yii.yz.admin.url({
                    route: '/admin/general/grid-view-settings',
                    params: {
                        data: $this.data()
                    }
                }, function (url) {
                    $.get(url, function (html) {
                        $('#grid-view-settings-wrapper').remove();
                        $('body').append(html);
                    }, 'html');
                });

                return false;
            });

            $('[data-grid-bind="selection"]').on('click', function (event) {
                var grid = $('#' + $(this).data('grid')),
                    url = $(this).attr('href'),
                    param = $(this).data('grid-param');
                var data = {};
                data[param] = grid.yiiGridView('getSelectedRows');
                url = url + (url.indexOf('?') > 0 ? '&' : '?') + $.param(data);

                $(this).data('method', 'post');
                $(this).attr('href', url);
                return yii.handleAction(this);
            });
        }
    };

    var pub = {
        init: function () {
            funcs.bindEvents();
        }
    };

    return pub;
})(jQuery);