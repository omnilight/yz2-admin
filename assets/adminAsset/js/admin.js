
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