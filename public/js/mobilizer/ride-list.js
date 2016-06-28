/**
 * @file
 * Ride Sharing Listing JS
 *
 * This entire file is one giant hack. 1 man dev team here fml.
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
(function ($, undefined) {

    var PACKAGE_NAME    = 'ride-list';
    var RideListing     = {};

    $("#route_results_holder").hide();

    jQuery(function($) {
        $(document).ready(function() {
            if ($("#app").attr("about") == PACKAGE_NAME) {
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
            }
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

        $("#submit_listing").click(function ()
        {
            if (!$("#disclaimer_accept").prop('checked')) {
                $("#disclaimer_accept_error").removeClass('hidden');
                return false;
            }

            var data = $.deparam($(form).serialize());

            try {
                RideListing.FormComponent.validateForm(data);
                // Validation passed!
                RideListing.FormComponent.ConfirmSubmission(data);
            } catch (validationErrors) {
                RideListing.FormComponent.handleFormValidationFail(validationErrors);
            }
        });

        // If the user confirms
        $("#submit_listing_confirm_now").click(function () {
            var data = $.deparam($(form).serialize());
            RideListing.FormComponent.prepareData(form, data);
            $(form).trigger('submit');
        });
    };

    RideListing.FormComponent.prepareData = function (form, data) {
        console.dir("preparing form data");
        $('<input />').attr('type', 'hidden').attr('name', 'starting_date').attr('value', data['start']).appendTo(form);
        $('<input />').attr('type', 'hidden').attr('name', 'ending_date').attr('value', data['end']).appendTo(form);

        if ('cat_friendly' in data) {
            $("#cat_friendly").attr('value', 1);
        }
        if ('dog_friendly' in data) {
            $("#dog_friendly").attr('value', 1);
        }
    };

    // Trigger modal and ask user to confirm listing
    RideListing.FormComponent.ConfirmSubmission = function (data) {

        // Get ready, shit's about to get ugly(er)...
        var content = "<h5><strong>Check that everything looks good and submit when you're ready.</strong></h5>";

        for (var key in data) {
            if (data.hasOwnProperty(key)) continue;
            content = content + '<div class="row"> <div class="col-md-4"> <strong>' + key + '</strong> </div> <div class="col-md-8" style="text-align:left;">' + data[data] + '</div></div><hr>';
        }

        // Party name
        content = content + '<div class="row"> <div class="col-md-4"> <strong>Party Name</strong> </div> <div class="col-md-8" style="text-align:left;">' + data['party_name'] + '</div></div><hr>';

        // Date ranges
        content = content + '<div class="row"> <div class="col-md-4"> <strong>Leaving Home On</strong> </div> <div class="col-md-8" style="text-align:left;">' + data['start'] + '</div></div><hr>';
        content = content + '<div class="row"> <div class="col-md-4"> <strong>Leaving Philly On</strong> </div> <div class="col-md-8" style="text-align:left;">' + data['end'] + '</div></div><hr>';

        // Passengers
        content = content + '<div class="row"> <div class="col-md-4"> <strong>Max Passengers</strong> </div> <div class="col-md-8" style="text-align:left;">' + data['max_occupants'] + '</div></div><hr>';

        // Location
        content = content + '<div class="row"> <div class="col-md-4"> <strong>Coming From</strong> </div> <div class="col-md-8" style="text-align:left;">' + data['location'] + '</div></div><hr>';

        if ('cat_friendly' in data) {
            content = content + '<div class="row"> <div class="col-md-4"> <strong>Cat</strong> </div> <div class="col-md-8" style="text-align:left;">Passengers can bring their cat</div></div><hr>';
        }
        if ('dog_friendly' in data) {
            content = content + '<div class="row"> <div class="col-md-4"> <strong>Dog</strong> </div> <div class="col-md-8" style="text-align:left;">Passengers can bring their dog</div></div><hr>';
        }

        content = content + '<div class="row"> <div class="col-md-4"> <strong>Additional Info</strong> </div> <div class="col-md-8" style="text-align:left;">' + data['additional_info'] + '</div></div><hr>';

        $('#listing_confirmation_content').html(content);

        $('#listing_confirmation_modal').modal('toggle');
    };

    RideListing.FormComponent.handleFormValidationFail = function(validationErrors) {
        // The user messed up, let em' know what's up
        var content = '<div class="alert alert-danger" role="alert">Cannot submit form. Please correct the following errors:</div>';

        for (var i = 0; i < validationErrors.length; i++)
        {
            content = content + "<p>Please specify <strong>" + validationErrors[i] + "</strong>.</p>";
        }

        $("#submit_error_modal_content").html(content);
        $("#submit_error_modal").modal('toggle');
    };

    RideListing.FormComponent.validateForm = function (data) {
        var requiredFields = {'party_name':"a party name", 'start':"a starting location", 'time_of_day':"the time approximation of when you're planning to leave", 'end':"the date you're coming back", 'max_occupants':"the maximum number of people you can bring, 1 at minimum.", 'location':"a starting location", 'additional_info':"additional information in the text box.", 'overview_path':" the route that you will be driving on. Or select and deselect?"};
        var messages = [];
        var validators = {
            'max_occupants': function (value) {
                value = $.trim(value);
                if (isNaN(value)) {
                    throw "– Maximum number of passengers is supposed to be a number, you entered \"" + value + "\"";
                } else if(value == 0) {
                    throw "maximum number of passengers must be greater than zero.";
                }
            },
            'additional_info': function(value) {
                if (value.length < 50){
                    throw "Enter at least 50 characters of additional information";
                }
            },
            'party_name': function(value) {
                if (value.length < 8) {
                    throw "Your party name should be at least 8 characters"
                }
            }
        };

        for (var field in requiredFields) {
            if (!requiredFields.hasOwnProperty(field)) continue;

            if (!field in data || !$.trim(data[field]).length) {
                messages.push(requiredFields[field]);
                continue;
            }

            if (field in validators) {
                try {
                    validators[field](data[field]);
                } catch (message) {
                    messages.push(message);
                }
            }
        }

        if (messages.length) {
            throw messages;
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
        var route_name              = $("#name");

        $("#number_of_routes").html(result.routes.length);

        // Set first route as default selected route
        selected_user_route.val(result.routes[0]["overview_polyline"]);
        route_name.val(result.routes[0]["summary"]);

        if (result.routes.length > 1) {
            $("#route_plural").html('s');
        }

        // Let's figure out how many routes we've received
        displayService.setDirections(result);

        for (var i = 0; i < result.routes.length; i++) {
            var class_ = i == 0 ? "user_route_item_active" : '';
            var icon_class = i == 0 ? "" : 'hidden';
            list_r = list_r + '<li style="padding-bottom:3px;" class="booking-item user_route_item ' + class_ + '" id="' + i + '"><i class="fa fa-car"></i><span class="booking-item-feature-title"><span class="via">via</span> ' + result.routes[i].summary + '<br><span style="color:darkgreen">' + result.routes[i].legs[0].distance.text + ' | ' + result.routes[i].legs[0].duration.text + '</span></span> <i class="' + icon_class + ' fa-check-circle-o fa-check-icon_selected_active-o icon_selected_active fa icon_selected"></i></li>';
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
            route_name.val(result.routes[selected_route]["summary"]);
        });
    };

    RideListing.MapComponent.handleNoMatchingRoute = function () {

    };

}(window.jQuery));