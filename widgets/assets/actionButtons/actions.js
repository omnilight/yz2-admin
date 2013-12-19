
yii.yz.admin.actionButtons = (function($) {
    var pub = {
        init: function() {
            $('#action-button-search').on('click', function(){
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
        }
    };

    return pub;
})(jQuery);