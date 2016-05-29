/**
 * @file
 * Allows interfacing with the mobilizer rest api
 */

(function ($, undefined) {

    $.mobilizerAPI  = function(options)
    {
        var data_app = {MOBILIZER_DATA_URL: ''};

        data_app.options = $.extend({
            'production': true,
            'base_url': 'https://www.seeyouinphilly.com/api',
            'apiKey': '',
            'return_resource' : false,
            'async':false
        }, options);

        var base_url = data_app.options.base_url;

        var ajax_request = function (request_data, async, callback)
        {
            var temp_response, r_async;

            if (typeof async == 'boolean') {
                r_async = async;
            } else {
                r_async = data_app.options.async;
            }

            if (data_app.options.return_resource) {
                return $.ajax({
                    url: data_app.MOBILIZER_DATA_URL,
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
                    url: data_app.MOBILIZER_DATA_URL,
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
        }
    }

}(window.jQuery));