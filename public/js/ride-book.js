(function ($, undefined) {

    var $window = $(window);

    if ($('#map-canvas').length) {

        $("#route_results_holder").hide();

        var map,
            marker,
            infoWindow,
            autocomplete,
            directionsDisplay,
            directionsService,
            infoWindowOptions;

        var destLat = 39.901096;
        var destLong = -75.171874;

        jQuery(function($) {
            $(document).ready(function() {
                var latlng          = new google.maps.LatLng(destLat, destLong);
                var myOptions       = {
                    zoom: 16,
                    center: latlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    scrollwheel: false
                };

                map                 = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
                directionsDisplay   = new google.maps.DirectionsRenderer();
                directionsService   = new google.maps.DirectionsService();
                autocomplete        = new google.maps.places.Autocomplete((document.getElementById('autocomplete')), {type: ['geocode']});
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
                        provideRouteAlternatives: true
                    };

                    directionsService.route(directionsRequest, function(result, status) {
                        if (status == google.maps.DirectionsStatus.OK) {
                            handleRouteDisplay(directionsDisplay, result);
                        } else {

                        }
                    });
                });
            });
        });

        function handleRouteDisplay(displayService, result) {
            var list_r = '';
            var domRouteResults = $("#route_results");
            var domRouteResultsHolder = $("#route_results_holder");
            var starting = $("#autocomplete").val();

            $("#number_of_routes").html(result.routes.length);

            if (result.routes.length > 1) {
                $("#route_plural").html('s');
            }

            // Let's figure out how many routes we've received
            displayService.setDirections(result);

            for (var i = 0; i < result.routes.length; i++) {
                list_r = list_r + '<li class="booking-item user_route_item" id="' + i + '"><i class="fa fa-car"></i><span class="booking-item-feature-title"><span class="via">via</span> ' + result.routes[i]["summary"] +' </li>';
            }

            starting = starting.split(',');

            $("#starting_address").html(starting[starting.length-3] + ", " + starting[starting.length-2]);
            domRouteResults.html(list_r);


            if (!domRouteResultsHolder.is(":visible")) {
                domRouteResultsHolder.slideDown();
            }

            $(".user_route_item").click(function () {
                displayService.setRouteIndex(parseInt($(this).attr("id")));
            });
        }

    }
}(window.jQuery));