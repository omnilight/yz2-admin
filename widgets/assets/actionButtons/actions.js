
yii.yz.admin.actionButtons = (function($) {
    var pub = {
        init: function() {
            $('#action-button-search').on('click', function() {
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
            $('#action-button-delete-checked').on('click', function() {
                var grid = $('#'+$(this).data('grid'));
                var selectedIds = grid.yiiGridView('getSelectedRows');
                console.log(selectedIds);
            })
        }
    };

    return pub;
})(jQuery);