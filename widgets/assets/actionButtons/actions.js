yii.yz.admin.actionButtons = (function ($) {
    var pub = {
        init: function () {
            $('#action-button-search').on('click', function () {
                if ($('#filter-search').hasClass('hidden')) {
                    $('#filter-search')
                        .removeClass('hidden')
                        .addClass('show');
                } else {
                    $('#filter-search')
                        .removeClass('show')
                        .addClass('hidden');
                }
            });
            $('#action-button-delete-checked').on('click', function (event) {
                var grid = $('#' + $(this).data('grid'));
                var url = $(this).attr('href');
                var data = {id: grid.yiiGridView('getSelectedRows')};
                url = url + (url.indexOf('?') > 0 ? '&' : '?') + $.param(data);

                $(this).data('method', 'post');
                $(this).attr('href', url);
                return yii.handleAction(this);
            })
        }
    };

    return pub;
})(jQuery);