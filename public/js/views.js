(function ($, undefined) {

    var app_views = {
        views: {
            route_list_single: '<li><i class="fa fa-car"></i><span class="booking-item-feature-title">t</span></li>'
        },
        _get: function (name) {
            return this.views[name];
        },
        // For out
        _export: function (contents) {
            for (var selector in contents) {
                $(selector).html(contents[selector]);
            }
        }
    }

}(window.jQuery));