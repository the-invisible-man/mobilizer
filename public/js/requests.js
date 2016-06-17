/**
 * @file
 * User Requests
 *
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
(function ($, undefined) {

    var PACKAGE_NAME        = 'requests-received';
    var ReceivedRequests    = {'token':null};

    jQuery(function($) {
        $(document).ready(function () {
            var app = $("#app");
            if (app.attr('about') == PACKAGE_NAME) {
                var packages = {
                    TabControl: {
                        trigger: '.requests_trigger',
                        requests_holder: '#requests-list'
                    }
                };

                ReceivedRequests.token    = app.attr('data-token');
                ReceivedRequests.api      = $.mobilizerAPI({token:ReceivedRequests.getToken()});
                registerEvents(packages);

                // By default show pending
                ReceivedRequests.TabControl.display_requests('pending');
            }
        });
    });

    function registerEvents(packages) {
        for (var _package in packages) {
            if (!packages.hasOwnProperty(_package)) continue;

            ReceivedRequests[_package]['_register'](packages[_package]);

            // Set config
            ReceivedRequests.TabControl['config'] = packages[_package];
        }
    }

    ReceivedRequests.TabControl         = {};
    ReceivedRequests.TabControl.config  = {};
    ReceivedRequests.getToken           = function () {
        return this.token;
    };

    ReceivedRequests.TabControl._register = function (config) {
        $(config['trigger']).click(function () {
            $(ReceivedRequests.TabControl.config['requests_holder']).html('Loading...');
            var status = $(this).attr('data-status');

            $(config['trigger']).removeClass('active');
            $(this).addClass('active');

            ReceivedRequests.TabControl.display_requests(status);
        });
    };

    ReceivedRequests.TabControl.display_requests = function (status) {
        ReceivedRequests.api.get_booking_requests(status, function (data) {
            if ($.isEmptyObject(data)) {
                $(ReceivedRequests.TabControl.config['requests_holder']).html('You don\'t have any ' + status + ' requests');
                return;
            }

            // Build view
            $(ReceivedRequests.TabControl.config['requests_holder']).html(ReceivedRequests.TabControl.build_view(data));
        });
    };

    ReceivedRequests.TabControl.build_view = function (data)
    {
        var content = '';
        var current_party = '';

        for (var key in data) {
            if (!data.hasOwnProperty(key)) continue;

            if (current_party != data[key]['listing']['party_name']) {
                content += '<h4><strong>'+ data[key]['listing']['party_name'] + '</strong></h4>';
                current_party = data[key]['listing']['party_name'];
            }

            content += '<li class="request_result booking-item" about="'+ data[key]['id'] + '">';
                content += '<div class="row">';
                    content += '<div class="col-md-4 col-xs-12">';
                        content += '<h5>' + data[key]['user']['first_name'] + ' ' + data[key]['user']['last_name'] + '</h5>';
                        content += '<p>' + data[key]['additional_info'] + '</p>';
                    content += '</div>';
                    content += '<div class="col-md-5" style="font-size: small">';
                        content += '<div class="col-md-6 col-xs-6">';
                            content += 'Pick Up Location<br>';
                            content += 'Passengers<br>';
                            content += 'Date Submitted<br>';
                        content += '</div>';
                        content += '<div class="col-md-6 col-xs-6" style="font-weight: bold">';
                            content += data[key]['user_location']['city'] + ' ' + data[key]['user_location']['state'] + '<br>';
                            content += data[key]['total_people'] + '<br>';
                            content += data[key]['date_submitted'] + '<br>';
                        content += '</div>';
                    content += '</div>';
                    content += '<div class="col-md-3 col-xs-12" style="margin-top: 30px;">';
                        content += '<center><span class="btn btn-primary">Review Request</span></center>';
                    content += '</div>';
                content += '</div>';
            content += '</li>';
        }

        return content;
    };

}(window.jQuery));