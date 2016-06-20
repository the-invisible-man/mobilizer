/**
 * @file
 * User Listings
 *
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
(function ($, undefined) {

    var PACKAGE_NAME    = 'my-listings';
    var MyListings      = {token: null};

    // Constructor type of thing
    jQuery(function($) {
        $(document).ready(function() {
            var app = $("#app");
            if (app.attr('about') == PACKAGE_NAME) {
                var packages = {
                    MainDisplay: {
                        listings_holder: '#listings-list',
                        trigger: '.listing_result',
                        mapId: 'map-canvas'
                    },
                    EditWindow: {
                        mapId: 'map-canvas',
                        trigger: '.listing_result',
                        listings_holder: '#listings-list'
                    }
                };


                MyListings.token    = app.attr('data-token');
                MyListings.api      = $.mobilizerAPI({token: MyListings.getToken()});

                registerEvents(packages);

                MyListings.MainDisplay.load();
            }
        });
    });

    function registerEvents(packages) {
        for (var _package in packages) {
            if (!packages.hasOwnProperty(_package)) continue;

            // set config
            MyListings[_package].config = packages[_package];

            // Boot package
            MyListings[_package]['boot'](packages[_package]);
        }
    }

    MyListings.dataStore    = {};
    MyListings.MainDisplay  = {};
    MyListings.EditWindow   = {};
    MyListings.getToken     = function () {
        return this.token;
    };

    MyListings.MainDisplay.boot = function (config)
    {
        var modal           = $("#edit_window");
        var destLat         = 39.901096;
        var destLong        = -75.171874;
        var latlng          = new google.maps.LatLng(destLat, destLong);
        var myOptions       = {
            zoom: 16,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: false
        };

        MyListings.EditWindow.map = new google.maps.Map(document.getElementById(config['mapId']), myOptions);

        var map             = MyListings.EditWindow.map;
        var tripRoute       = new google.maps.Polyline({
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            map: map
        });

        $(config['listings_holder']).on('click', config['trigger'], function () {
            var key     = $(this).attr('data-key');
            var data    = MyListings.dataStore[key];

            MyListings.EditWindow.fill(data);

            modal.modal('toggle');

            // Add map info
            modal.on('shown.bs.modal', function() {
                // Call this so the map doesn't show as a big grey box
                google.maps.event.trigger(map, "resize");

                // Decode overview path and draw to map
                var polyline            = google.maps.geometry.encoding.decodePath(data['route']['overview_path']);
                var bounds              = new google.maps.LatLngBounds();

                tripRoute.setPath(polyline);

                for (var i = 0; i < polyline.length; i++) {
                    bounds.extend(polyline[i]);
                }

                map.setCenter(tripRoute.getPath().getAt(Math.round(tripRoute.getPath().getLength() / 2)));
                map.fitBounds(bounds);
            });
        });
    };

    MyListings.EditWindow.boot = function (config)
    {

    };

    MyListings.EditWindow.fill = function (data)
    {
        $("#listing_party_name").val(data['party_name']);
        $("#listing_max_occupants").val(data['max_occupants']);
        $("#listing_additional_info").val(data['additional_info']);
        $("#listing_date_leaving").html(data['starting_date']);
        $("#listing_time_of_day").html(data['time_of_day']);
        $("#listing_date_returning").html(data['ending_date']);
        $("#listing_route_name").html('via ' + data['route']['name']);
    };

    MyListings.MainDisplay.load = function ()
    {
        MyListings.api.get_listings(function (data) {
            if (!data.length) {
                $(MyListings.MainDisplay.config['listings_holder']).html('You don\'t have any listings');
                return;
            }

            // Save data to this store
            MyListings.dataStore = data;

            // Build view
            $(MyListings.MainDisplay.config['listings_holder']).html(MyListings.MainDisplay.build_view(data));
        });
    };

    MyListings.MainDisplay.build_view = function (data)
    {
        var content = '';

        for (var key in data) {
            if (!data.hasOwnProperty(key)) continue;

            content += '<li class="listing_result booking-item" about="'+ data[key]['id'] + '" data-key="' + key + '" style="margin-bottom:20px;">';
                content += '<div class="row">';
                    content += '<div class="col-md-4 col-xs-12">';
                        content += '<h5>' + data[key]['party_name'] + '</h5>';
                        content += '<p>' + data[key]['additional_info'] + '</p>';
                    content += '</div>';
                    content += '<div class="col-md-5" style="font-size: small">';
                        content += '<div class="col-md-6 col-xs-6">';
                            content += 'Leaving From<br>';
                            content += 'Leaving On<br>';
                            content += 'Coming Back<br>';
                        content += '</div>';
                        content += '<div class="col-md-6 col-xs-6" style="font-weight: bold">';
                            content += data[key]['location']['city'] + ', ' + data[key]['location']['state'] + '<br>';
                            content += data[key]['starting_date'] + '<br>';
                            content += data[key]['ending_date'] + '<br>';
                        content += '</div>';
                    content += '</div>';
                    content += '<div class="col-md-3 col-xs-12" style="margin-top: 30px;">';
                        content += '<center><span class="btn btn-primary">Edit Listing</span></center>';
                    content += '</div>';
                content += '</div>';
            content += '</li>';
        }

        return content;
    };

}(window.jQuery));