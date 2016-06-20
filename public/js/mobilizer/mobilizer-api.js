/**
 * @file
 * Allows interfacing with the mobilizer rest api
 */

(function ($, undefined) {

    $.mobilizerAPI  = function(options)
    {
        var app = {};

        app.options = $.extend(config.fetch('api'), options);

        var base_url = app.options.base_url;

        var ajax_request = function (request_data, callback, resource, method)
        {
            var temp_response, r_async;

            // Figure out method
            if (typeof method == 'undefined') {
                // Default to get
                method = 'GET';
            }

            if (typeof async == 'boolean') {
                r_async = async;
            } else {
                r_async = app.options.async;
            }

            request_data['_token'] = app.options.token;

            if (app.options.return_resource) {
                return $.ajax({
                    url: base_url + resource,
                    data: request_data,
                    async: r_async,
                    method: method,
                    success: function (data, textStatus, jqXHR) {
                        if (typeof callback == 'function') {
                            callback(data);
                        }
                        temp_response = data;
                    }
                });
            } else {
                $.ajax({
                    url: base_url + resource,
                    data: request_data,
                    async: r_async,
                    method: method,
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

        app.get_listings = function (callback) {
            var data = {};
            var resource = 'listings';

            return ajax_request(data, callback, resource)
        };

        app.get_listing = function(listing_id, location, callback)
        {
            var data = {};
            if (location !== null) {
                data['location'] = location;
            }

            var resource = 'listings/' + listing_id;

            return ajax_request(data, callback, resource);
        };

        app.get_listing_contact_info = function(listing_id, callback)
        {
            var resource = 'listings/contact/' + listing_id;

            return ajax_request({}, callback, resource);
        };

        app.get_request_contact_info = function(booking_id, callback)
        {
            var resource = 'bookings/contact/' + booking_id;

            return ajax_request({}, callback, resource);
        };

        app.cancel_trip = function (booking_id, callback)
        {
            var resource = 'bookings/' + booking_id;

            return ajax_request({}, callback, resource, 'DELETE');
        };

        app.get_booking_requests = function (status, callback)
        {
            var resource = 'requests';
            var data = {};

            // Check if any specific status
            if (null != status) {
                data['status'] = status;
            }

            return ajax_request(data, callback, resource);
        };

        app.accept_request = function (booking_id, callback)
        {
            var resource = 'requests/' + booking_id + '/accept';

            return ajax_request({}, callback, resource);
        };

        app.reject_request = function (booking_id, callback)
        {
            var resource = 'requests/' + booking_id + '/reject';

            return ajax_request({}, callback, resource);
        };

        app.cancel_request = function (booking_id, callback)
        {
            var resource = 'requests/' + booking_id + '/cancel';

            return ajax_request({}, callback, resource);
        };

        return app;
    }

}(window.jQuery));