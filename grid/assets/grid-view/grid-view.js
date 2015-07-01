var adminGridView = (function($) {
    var funcs = {
        bindEvents: function() {
            $('.js-btn-admin-grid-settings').click(function (e) {
                e.preventDefault();

                var $this = $(this);
                yii.yz.admin.url({
                    route: '/admin/general/grid-view-settings',
                    params: {
                        data: $this.data()
                    }
                }, function(url) {
                    $.get(url, function(html) {
                        $('#grid-view-settings-wrapper').remove();
                        $('body').append(html);
                    }, 'html');
                });

                return false;
            })
        }
    };

    var pub = {
        init: function() {
            funcs.bindEvents();
        }
    };

    return pub;
})(jQuery);