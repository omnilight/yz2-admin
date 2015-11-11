
yii.yz.admin = (function($) {
    var settings = {
        routeToUrl: ''
    };
    var pub = {
        settings: settings,
        url: function(route, callback) {
            var url = settings.routeToUrl;
            $.ajax({
                url: url,
                data: {route: route},
                dataType: 'json',
                success:  function(data) {
                    callback(data.url);
                }
            });
        }
    };

    return pub;
})(jQuery);

// Window size check
$(function() {
    setTimeout(function() {
        var $table = $('.grid-view table.table'),
            $body = $('body'),
            $window = $(window);

        if ($table.length == 0) {
            return;
        }

        var desiredWidth = $table.offset().left + $table.width();

        $body.css({
            overflowX: 'auto',
            minWidth: desiredWidth
        });
    }, 200);
});