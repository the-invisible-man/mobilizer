/**
 * @file
 * Allows interfacing with the mobilizer rest api
 */

(function ($, undefined) {

    $.mobilizerAPI  = function(options)
    {
        var data_app = {MOBILIZER_DATA_URL: 'http://192.168.10.10/'};

        data_app.options = $.extend({
            'production': true,
            'base_url': 'http://192.168.10.10/',
            'apiKey': '',
            'return_resource' : false,
            'async':true
        }, options);

        var base_url = data_app.options.base_url;

        var ajax_request = function (request_data, callback, resource)
        {
            var temp_response, r_async;

            if (typeof async == 'boolean') {
                r_async = async;
            } else {
                r_async = data_app.options.async;
            }

            if (data_app.options.return_resource) {
                return $.ajax({
                    url: data_app.MOBILIZER_DATA_URL + resource,
                    data: request_data,
                    async: r_async,
                    success: function (data, textStatus, jqXHR) {
                        if (typeof callback == 'function') {
                            callback(data);
                        }
                        temp_response = data;
                    }
                });
            } else {
                $.ajax({
                    url: data_app.MOBILIZER_DATA_URL + resource,
                    data: request_data,
                    async: r_async,
                    success: function (data, textStatus, jqXHR) {
                        if (typeof  callback == 'function') {
                            callback(data, jqXHR);
                        }
                        temp_response = data;
                    },
                    error: function (jqXHR, textSatus, errorThrown) {
                        if (typeof callback == 'function') {
                            callback(errorThrown, jqXHR);
                        }
                    }
                })
            }

            return temp_response;
        };

        data_app.get_listing = function(listing_id, location, callback)
        {
            var data = {};
            if (location !== null) {
                data['location'] = location;
            }

            var resource = 'listings/' + listing_id;

            return ajax_request(data, callback, resource);
        };

        return data_app;
    }

}(window.jQuery));