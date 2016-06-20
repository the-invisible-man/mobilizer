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
                    RequestDisplay: {
                        trigger: '.request_result',
                        modal: '#request_window',
                        mapId: "map-canvas",
                        contact_section: "#contact_section",
                        contact_user: "#contact_user"
                    },
                    TabControl: {
                        trigger: '.requests_trigger',
                        requests_holder: '#requests-list'
                    }
                };

                ReceivedRequests.token    = app.attr('data-token');
                ReceivedRequests.api      = $.mobilizerAPI({token:ReceivedRequests.getToken()});
                registerEvents(packages);

                // By default show pending
                ReceivedRequests.TabControl.load('pending');
            }
        });
    });

    function registerEvents(packages) {
        for (var _package in packages) {
            if (!packages.hasOwnProperty(_package)) continue;

            ReceivedRequests[_package]['_register'](packages[_package]);

            // Set config
            ReceivedRequests[_package]['config'] = packages[_package];
        }
    }

    ReceivedRequests.TabControl         = {};
    ReceivedRequests.TabControl.config  = {};
    ReceivedRequests.RequestDisplay     = {};
    ReceivedRequests.dataStore          = {};
    ReceivedRequests.getToken           = function () {
        return this.token;
    };

    ReceivedRequests.TabControl._register = function (config)
    {
        $(config['trigger']).click(function () {
            $(ReceivedRequests.TabControl.config['requests_holder']).html('Loading...');
            var status = $(this).attr('data-status');

            $(config['trigger']).removeClass('active');
            $(this).addClass('active');

            ReceivedRequests.TabControl.load(status);
        });
    };

    ReceivedRequests.RequestDisplay._register = function (config)
    {
        var destLat         = 39.901096;
        var destLong        = -75.171874;
        var latlng          = new google.maps.LatLng(destLat, destLong);
        var myOptions       = {
            zoom: 16,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: false
        };

        var contact_user    = $("#contact_user");
        var contactSection  = $("#contact_section");
        var request_options = $("#request_options");
        var request_option_confirm = $("#request_option_confirm");
        var cancel_request_holder = $("#cancel_request_holder");


        contactSection.hide();
        ReceivedRequests.RequestDisplay.map = new google.maps.Map(document.getElementById(config['mapId']), myOptions);

        var infoWindow  = new google.maps.InfoWindow(),
            map         = ReceivedRequests.RequestDisplay.map,
            marker      = new google.maps.Marker({map: map}),
            modal       = $(config['modal']);

        var tripRoute           = new google.maps.Polyline({
                                    strokeColor: '#FF0000',
                                    strokeOpacity: 0.8,
                                    strokeWeight: 2,
                                    fillColor: '#FF0000',
                                    map: map
                                });

        contact_user.click(function () {
            contactSection.slideToggle();
        });

        $("#requests-list").on('click', config['trigger'], function () {
            var key     = $(this).attr('data-key');
            var data    = ReceivedRequests.dataStore[key];

            ReceivedRequests.RequestDisplay.fill(data);

            // Open Modal
            modal.modal('toggle');

            // Add map info
            modal.on('shown.bs.modal', function() {
                // Call this so the map doesn't show as a big grey box
                google.maps.event.trigger(map, "resize");

                // Decode overview path and draw to map
                var polyline            = google.maps.geometry.encoding.decodePath(data['listing']['overview_path']);
                var bounds              = new google.maps.LatLngBounds();
                var guestLL             = new google.maps.LatLng(data['user_location']['lat'], data['user_location']['long']);

                infoWindow.setContent(data['user']['first_name'] + ' - Pickup Location');
                marker.setPosition(guestLL);
                tripRoute.setPath(polyline);

                for (var i = 0; i < polyline.length; i++) {
                    bounds.extend(polyline[i]);
                }

                map.setCenter(tripRoute.getPath().getAt(Math.round(tripRoute.getPath().getLength() / 2)));
                map.fitBounds(bounds);
                var zoom = map.getZoom();
                map.setZoom(zoom+1);
                infoWindow.open(map, marker);
            });

            modal.on('hidden.bs.modal', function() {
                contactSection.hide();
            });
        });

        $("#request_reject").click(function () {
            var id = $(this).attr('data-id');

            request_option_confirm.attr('data-action', 'reject');
            request_option_confirm.attr('data-id', id);

            request_option_confirm.removeClass('hidden');
            $("#request_option_confirm_message").html('You are about to <strong>deny</strong> this request, continue?');
            request_options.addClass('hidden');
        });

        $("#request_accept").click(function () {
            var id = $(this).attr('data-id');

            request_option_confirm.attr('data-action', 'accept');
            request_option_confirm.attr('data-id', id);

            request_option_confirm.removeClass('hidden');
            $("#request_option_confirm_message").html('You are about to <strong>accept</strong> this request, continue?');
            request_options.addClass('hidden');
        });

        $("#cancel_request").click(function () {
            var id = $(this).attr('data-id');

            request_option_confirm.attr('data-action', 'cancel');
            request_option_confirm.attr('data-id', id);

            cancel_request_holder.addClass('hidden');

            request_option_confirm.removeClass('hidden');
            $("#request_option_confirm_message").html('You are about to <strong>cancel</strong> this request, continue?');
            cancel_request_holder.addClass('hidden');
        });

        request_option_confirm.click(function () {
            var id = $(this).attr('data-id');
            var action = $(this).attr('data-action');

            if (action == 'accept') {
                ReceivedRequests.api.accept_request(id, function () {
                    // Reload requests
                    ReceivedRequests.TabControl.load('pending');
                    modal.modal('toggle');
                });
            } else {
                ReceivedRequests.api.reject_request(id, function () {
                    // Reload requests
                    ReceivedRequests.TabControl.load('pending');
                    modal.modal('toggle');
                });
            }
        });
    };

    ReceivedRequests.RequestDisplay.fill = function(data)
    {
        $("#request_party_name").html(data['listing']['party_name']);
        $("#request_guest_name").html(data['user']['first_name'] + ' ' + data['user']['last_name']);
        $("#request_total_people").html(data['total_people']);
        $("#request_pickup_location").html(data['user_location']['city'] + ', ' + data['user_location']['state']);
        $("#request_additional_info").html(data['additional_info']);
        $("#listing_starting_date").html(data['listing']['starting_date']);
        $("#listing_location").html(data['listing']['location']['city'] + ', ' + data['listing']['location']['state']);
        $("#listing_leaving").html(data['listing']['time_of_day']);
        $("#listing_max_occupants").html(data['listing']['max_occupants']);
        $("#listing_remaining_slots").html(data['listing']['remainingSlots']);
        $("#request_date_submitted").html(data['date_submitted']);
        $("#request_contact_email").val(data['user']['email']);
        $("#request_contact_email_mobile").html(data['user']['email']);
        $("#contact_name_short").html(data['user']['first_name']);
        $("#cancel_request").attr('data-id', data['id']);

        var request_options = $("#request_options");
        var no_request_option = $("#no_request_options");
        var no_request_option_message = $("#no_request_option_message");
        var cancel_request_holder = $("#cancel_request_holder");
        var cancel_request = $("#cancel_request");
        var request_option_confirm = $("#request_option_confirm");

        cancel_request_holder.addClass('hidden');
        no_request_option.addClass('hidden');
        no_request_option_message.addClass('hidden');
        request_options.addClass('hidden');
        request_option_confirm.addClass('hidden');

        if (data['status'] == 'pending')
        {
            if (data['total_people'] > data['listing']['remainingSlots']) {
                no_request_option.removeClass('hidden');
                no_request_option_message.html('Not Enough Seats Available to Accept');
            } else {
                request_options.removeClass('hidden');
            }

            request_option_confirm.addClass('hidden');

            $("#request_reject").attr('data-id', data['id']);
            $("#request_accept").attr('data-id', data['id']);
        } else {
            no_request_option_message.html('You Accepted This Request');
            no_request_option.removeClass('hidden');
            cancel_request_holder.removeClass('hidden');

        }
    };

    ReceivedRequests.TabControl.load = function (status)
    {
        ReceivedRequests.api.get_booking_requests(status, function (data) {
            if (!data.length) {
                console.dir(ReceivedRequests.TabControl.config['requests_holder']);
                $(ReceivedRequests.TabControl.config['requests_holder']).html('You don\'t have any ' + status + ' requests');
                return;
            }

            // Save data to this store
            ReceivedRequests.dataStore = data;

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

            if (current_party != data[key]['party_name']) {
                content += '<h4 style="padding-top:20px;"><strong>'+ data[key]['listing']['party_name'] + '</strong></h4>';
                current_party = data[key]['listing']['party_name'];
            }

            content += '<li class="request_result booking-item" about="'+ data[key]['id'] + '" data-key="' + key + '">';
                content += '<div class="row">';
                    content += '<div class="col-md-4 col-xs-12">';
                        content += '<h5>' + data[key]['user']['first_name'] + ' ' + data[key]['user']['last_name'] + '</h5>';
                        content += '<p>' + data[key]['additional_info'] + '</p>';
                    content += '</div>';
                    content += '<div class="col-md-5" style="font-size: small">';
                        content += '<div class="col-md-6 col-xs-6">';
                            content += 'Pickup Location<br>';
                            content += 'Passengers<br>';
                            content += 'Date Submitted<br>';
                        content += '</div>';
                        content += '<div class="col-md-6 col-xs-6" style="font-weight: bold">';
                            content += data[key]['user_location']['city'] + ', ' + data[key]['user_location']['state'] + '<br>';
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