/**
 * @file
 * Ride Sharing Listing JS
 *
 * Totally winged this logic. I should have used Angular but I
 * had not time to relearn it.
 */
(function ($, undefined) {

    var RideListing = {};

    $("#route_results_holder").hide();

    jQuery(function($) {
        $(document).ready(function() {
            var packages = {
                MapComponent: {
                    mapElementId: "map-canvas",
                    autocompleteElementId: "autocomplete"
                },

                FormComponent: {
                    formElementId: "list_user_ride_form"
                }
            };

            // Avoid events from being bound twice, seriously that shit's annoying
            registerEvents(packages);
        });
    });

    function registerEvents(packages) {
        for (var _package in packages) {
            if (!packages.hasOwnProperty(_package)) continue;

            RideListing[_package]['_register'](packages[_package]);
        }
    }

    RideListing.FormComponent = {};

    RideListing.FormComponent._register = function (config) {

        var form = $("#" + config['formElementId']);

        form.submit(function (event) {
            // Let's make sure all data we need is being submitted
            data = $.deparam($(this).serialize());
            event.preventDefault();
            console.dir(data);
            try {
                RideListing.FormComponent.validateForm(data);
            } catch (err) {
                event.preventDefault();
                RideListing.FormComponent.handleFormValidationFail(err);
            }
        });
    };

    RideListing.FormComponent.handleFormValidationFail = function(err) {
        // fug it
        alert(err);

    };

    RideListing.FormComponent.validateForm = function (data) {
        var validators = {
            'party_name': function (value) {

            },
            'max_occupants': function (value) {
                if (isNaN($.trim(value))) {
                    throw "Maximum number of passengers is supposed to be a number, you entered \"" + value + "\"";
                }
            }
        };

        for (key in data) {
            if (!data.hasOwnProperty(key)) continue;

            if (validators.hasOwnProperty(key)) {
                validators[key](data[key]);
            }
        }
    };

    RideListing.MapComponent = {};

    RideListing.MapComponent._register = function (config) {
        if (!$('#' + config['mapElementId']).length){
            throw "Cannot initialize map because #map-canvas doesn't exist in the doc";
        }

        var map,
            marker,
            infoWindow,
            autocomplete,
            directionsDisplay,
            directionsService,
            infoWindowOptions;

        var destLat         = 39.901096;
        var destLong        = -75.171874;

        var latlng          = new google.maps.LatLng(destLat, destLong);
        var myOptions       = {
            zoom: 16,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: false
        };

        map                 = new google.maps.Map(document.getElementById(config['mapElementId']), myOptions);
        directionsDisplay   = new google.maps.DirectionsRenderer();
        directionsService   = new google.maps.DirectionsService();
        autocomplete        = new google.maps.places.Autocomplete((document.getElementById(config['autocompleteElementId'])), {type: ['geocode']});
        marker              = new google.maps.Marker({position: latlng, map: map});
        infoWindowOptions   = {content: "The Democratic National Convention will take place here at the Wells Fargo Center"};
        infoWindow          = new google.maps.InfoWindow(infoWindowOptions);


        // Bind elements to body
        marker.setMap(map);
        autocomplete.bindTo('bounds', map);
        directionsDisplay.setMap(map);


        google.maps.event.addListener(marker, 'click', function(e) {
            infoWindow.open(map, marker);
        });

        $('a[href="#google-map-tab"]').on('shown.bs.tab', function(e) {
            google.maps.event.trigger(map, 'resize');
            map.setCenter(latlng);
        });

        $("#autocomplete").on("keyup keypress", function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });

        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var wellFargoCenter     = new google.maps.LatLng(destLat, destLong);
            var origin              = autocomplete.getPlace();
            var originLatLong       = new google.maps.LatLng(origin.geometry.location.lat(), origin.geometry.location.lng());

            var directionsRequest   = {
                origin: originLatLong,
                destination: wellFargoCenter,
                travelMode: google.maps.TravelMode.DRIVING,
                provideRouteAlternatives: true,
                unitSystem: google.maps.UnitSystem.IMPERIAL
            };

            directionsService.route(directionsRequest, function(result, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    RideListing.MapComponent.handleRouteDisplay(directionsDisplay, result);
                } else {
                    RideListing.MapComponent.handleNoMatchingRoute();
                }
            });
        });

    };

    RideListing.MapComponent.handleRouteDisplay = function(displayService, result) {
        var list_r                  = '';
        var domRouteResults         = $("#route_results");
        var domRouteResultsHolder   = $("#route_results_holder");
        var starting                = $("#autocomplete").val();
        var selected_user_route     = $("#overview_path");

        $("#number_of_routes").html(result.routes.length);

        // Set first route as default selected route
        selected_user_route.val(result.routes[0]["overview_polyline"]);

        if (result.routes.length > 1) {
            $("#route_plural").html('s');
        }

        // Let's figure out how many routes we've received
        displayService.setDirections(result);

        for (var i = 0; i < result.routes.length; i++) {
            var class_ = i == 0 ? "user_route_item_active" : '';
            var icon_class = i == 0 ? "" : 'hidden';
            list_r = list_r + '<li style="padding-bottom:3px;" class="booking-item user_route_item ' + class_ + '" id="' + i + '"><i class="fa fa-car"></i><span class="booking-item-feature-title"><span class="via">via</span> ' + result.routes[i].summary + '<br><span style="color:green">' + result.routes[i].legs[0].distance.text + ' | ' + result.routes[i].legs[0].duration.text + '</span></span> <i class="' + icon_class + ' fa-check-circle-o fa-check-icon_selected_active-o icon_selected_active fa icon_selected"></i></li>';
        }

        starting = starting.split(',');

        $("#starting_address").html(starting[starting.length-3] + ", " + starting[starting.length-2]);
        domRouteResults.html(list_r);


        if (!domRouteResultsHolder.is(":visible")) {
            domRouteResultsHolder.slideDown();
        }

        $(".user_route_item").click(function () {

            var icon_selected = $(".icon_selected");
            var selected_route = parseInt($(this).attr("id"));

            $(".user_route_item").removeClass("user_route_item_active");
            $(this).addClass("user_route_item_active");
            icon_selected.addClass("hidden");
            $(".icon_selected", this).removeClass("hidden");
            displayService.setRouteIndex(parseInt($(this).attr("id")));
            selected_user_route.val(result.routes[selected_route]["overview_polyline"]);
        });
    };

    RideListing.MapComponent.handleNoMatchingRoute = function () {

    };

}(window.jQuery));