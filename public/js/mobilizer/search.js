/**
 * @file
 * Search Listings JS
 *
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
(function ($, undefined) {

    var PACKAGE_NAME = 'search';
    var SearchController = {'token':null};

    jQuery(function($) {
        $(document).ready(function () {
            var app = $("#app");
            if (app.attr('about') == PACKAGE_NAME) {
                var packages = {
                    SearchComponent: {
                        autocompleteElementId: "autocomplete"
                    },
                    Results: {
                        resultElementId: "listing_result",
                        autocompleteElementId: "autocomplete",
                        ResultModalId: "listing_info_window"
                    }
                };

                // Avoid events from being bound twice, seriously that shit's annoying
                SearchController.token = app.attr('data-token');
                registerEvents(packages);
            }
        });
    });

    function registerEvents(packages) {
        for (var _package in packages) {
            if (!packages.hasOwnProperty(_package)) continue;

            SearchController[_package]['_register'](packages[_package]);
        }
    }

    function isValidEmailAddress(emailAddress) {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        return pattern.test(emailAddress);
    };

    SearchController.SearchComponent = {};
    SearchController.Results = {};
    SearchController.getToken = function () {
        return this.token;
    };

    SearchController.Views = {
        views: {
            login_window: $('#login_window'),
            booked_window: $('#booked_window'),
            booking_form_window: $('#booking_form_window')
        },
        show: function (view, data, closure) {
            for (var key in SearchController.Views.views) {
                if (!SearchController.Views.views.hasOwnProperty(key)) continue;

                SearchController.Views.views[key].addClass('hidden');
            }
            SearchController.Views.views[view].removeClass('hidden');

            if (typeof(closure) == 'function') {
                closure(data);
            }
        }
    };

    SearchController.Results.contains = function(needle) {
        // Per spec, the way to identify NaN is that it is not equal to itself
        var findNaN = needle !== needle;
        var indexOf;

        if(!findNaN && typeof Array.prototype.indexOf === 'function') {
            indexOf = Array.prototype.indexOf;
        } else {
            indexOf = function(needle) {
                var i = -1, index = -1;

                for(i = 0; i < this.length; i++) {
                    var item = this[i];

                    if((findNaN && item !== item) || item === needle) {
                        index = i;
                        break;
                    }
                }

                return index;
            };
        }

        return indexOf.call(this, needle) > -1;
    };

    SearchController.SearchComponent._register = function (config){
        var autocomplete        = new google.maps.places.Autocomplete((document.getElementById(config['autocompleteElementId'])), {type: ['geocode']});

        $("#ride_search_form").submit(function () {
            if (!$("#autocomplete").val().length) {
                $("#ride_search_error").html();
                $("#ride_search_error").html('<strong>Enter an address into the search field</strong>');
                return false;
            }
        });
    };

    SearchController.Results._register = function (config) {

        $("#email_notify_form").submit(function () {
            var email = $("#email_notify_field").val();

            if (!isValidEmailAddress(email)) {
                $("#email_notify_error").html('Please enter a valid email address');
                return false;
            }
        });

        // Bind actions of each result
        $(document.getElementsByClassName(config['resultElementId'])).click(function () {

            var id = $(this).attr('about');
            var search = $('#' +(config['autocompleteElementId']));

            if (!search.val().length) {
                SearchController.Results.listingInfo(id);
            } else {
                SearchController.Results.listingInfo(id, search.val());
            }
        });

        var booking_text_field = $("#booking_additional_info");
        var characterCountPlaceHolder = $("#additional_info_character_count");

        // Character count for textbox
        booking_text_field.keydown(function () {
            var cs = $(this).val().length;
            characterCountPlaceHolder.text(cs);
        });

        booking_text_field.keyup(function () {
            var cs = $(this).val().length;
            characterCountPlaceHolder.text(cs);
        });

    };

    SearchController.Results.cache = {};

    SearchController.Results.listingInfo = function (listing_id, location) {

        if (listing_id in SearchController.Results.cache) {
            var listing_data = SearchController.Results.cache[listing_id];
            SearchController.Results.listingInfoView(listing_data);
        } else {
            $.mobilizerAPI().get_listing(listing_id, location, function (listing_data) {
                SearchController.Results.cache[listing_id] = listing_data;
                SearchController.Results.listingInfoView(listing_data);
            });
        }
    };

    SearchController.Results.listingInfoView = function (listing_data) {
        if (listing_data['type'] == 'R') {
            SearchController.Results.ride(listing_data);
        } else {
            SearchController.Results.housing(listing_data);
        }

        $("#party_name_placeholder").html(listing_data['party_name']);
        $("#listing_info_window_additional_info").val(listing_data['additional_info']);
        $('#listing_info_window').modal('toggle');
        $("#booking_form").ready(function () {
            SearchController.Results.bindBookingFormEvents('booking_form', listing_data);
        });
        // Add autocomplete
        new google.maps.places.Autocomplete((document.getElementById('booking_pickup_location')), {type: ['geocode']});
    };

    SearchController.Results.ride = function (data) {

        $("#host_").html(data['host']);
        $("#location_").html(data['location']['city'] + ', ' + data['location']['state']);
        $("#starting_date_raw_").html(data['starting_date_raw']);
        $("#time_of_day_string_").html(data['metadata']['time_of_day_string']);
        $("#pickup_date_").html(data['pickup_time']['date']);
        $("#pickup_time_").html(data['pickup_time']['time']);
        $("#data_remaining_slots_").html(data['remaining_slots']);

        if (!data['auth']['status']) {
            SearchController.Views.show('login_window');
        } else {
            if (SearchController.Results.contains.call(data['user_bookings'], data['id'])) {
                SearchController.Views.show('booked_window');
            } else {
                SearchController.Views.show('booking_form_window', data, function(data) {
                    $("#booking_form_token_").val(SearchController.getToken());
                    $("#booking_form_fk_listing_id_").val(data['id']);
                    $("#booking_form_type_").val(data['type']);
                    $("#booking_pickup_location").val(SearchController.Results.getQuery());
                    $("#booking_form_window_location").val(SearchController.Results.getQuery());
                    $("#total_people_field").val($("#total_people").val());
                    $("#host_inline").html(data['host']);
                });
            }
        }
    };

    SearchController.Results.getQuery = function()
    {
        return $("#original_query").val();
    };

    SearchController.Results.bindBookingFormEvents = function (id, listing_data) {
        // Form logic for validation and what not
        $(document).on('submit', "#booking_form", function () {
            try {
                SearchController.Results.validateBooking(listing_data);
            } catch (errs) {
                // Logic for displaying errors to user
                SearchController.Results.showFormErrors(errs);
                return false;
            }
        });

    };

    SearchController.Results.showFormErrors = function (errors) {
        for (var key in errors) {
            if(!errors.hasOwnProperty(key)) continue;
            var error_block = $("#" + key + "_error");
            error_block.html(errors[[key]]);
            error_block.removeClass('hidden');
        }
    };

    SearchController.Results.updateCharacterCount = function (selector) {
        var cs = $(this).val().length;
        console.dir(cs);
        selector.text(cs);
    };

    SearchController.Results.validateBooking = function (listing_data) {
        // Hide any errors from last submit


        $("#booking_additional_info_error").addClass('hidden');
        $("#total_people_error").addClass('hidden');
        $("#disclaimer_accept_error").addClass('hidden');
        $("#tos_accept_error").addClass('hidden');

        var errors = {};

        var total_people = $("#total_people_field").val();

        if ($("#booking_additional_info").val().length < 50) {
            errors['booking_additional_info'] = 'Please enter at least 50 characters of information';
        }

        if (total_people > listing_data['remaining_slots']) {
            errors['total_people'] = 'You can\'t book ' + total_people  + ' people in this ride. There\'s only ' + listing_data['remaining_slots'] + ' seats left.';
        }

        if (!$("#tos_accept").prop('checked')) {
            errors['tos_accept'] = 'You must accept the terms of service to continue.';
        }

        if (!$("#disclaimer_accept").prop('checked')) {
            errors['disclaimer_accept'] = 'You must accept the disclaimer to continue.';
        }

        var count = 0;

        for(var prop in errors) {
            if(errors.hasOwnProperty(prop))
                ++count;
        }

        if (count)
        {
            throw errors;
        }
    };

    SearchController.Results.housing = function (data) {

    };

}(window.jQuery));