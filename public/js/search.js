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

    SearchController.SearchComponent = {};
    SearchController.Results = {};
    SearchController.getToken = function () {
        return this.token;
    };

    SearchController.SearchComponent._register = function (config){
        var autocomplete        = new google.maps.places.Autocomplete((document.getElementById(config['autocompleteElementId'])), {type: ['geocode']});
    };

    SearchController.Results._register = function (config) {

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
        // handle display

        var content = '';

        if (listing_data['type'] == 'R') {
            content = SearchController.Results.ride(listing_data);
        } else {
            content = SearchController.Results.housing(listing_data);
        }

        $("#party_name_placeholder").html(listing_data['party_name']);
        $("#listing_info_window_additional_info").html(listing_data['additional_info']);
        $("#listing_content").html(content);
        $('#listing_info_window').modal('toggle');
        $("#booking_form").ready(function () {
            SearchController.Results.bindBookingFormEvents('booking_form', listing_data);
        });
        // Add autocomplete
        new google.maps.places.Autocomplete((document.getElementById('booking_pickup_location')), {type: ['geocode']});
    };

    SearchController.Results.ride = function (data) {
        var content = '<div class="row">';

            content += '<div class="col-md-6" style="margin-bottom:10px">';
                content += '<strong>You\'ll be riding with:</strong><br>';
            content += '</div>';
            content += '<div class="col-md-6" style="margin-bottom:10px">';
                content += data['host'] + '<br>';
            content += '</div>';

            content += '<div class="col-md-6" style="margin-bottom:10px">';
                content += '<strong>Driving From:</strong><br>';
            content += '</div>';
            content += '<div class="col-md-6" style="margin-bottom:10px">';
                content += data['location']['city'] + ', ' + data['location']['state'] + '<br>';
            content += '</div>';

            content += '<div class="col-md-6" style="margin-bottom:10px">';
                content += '<strong>Driver Leaving Home:</strong><br>';
            content += '</div>';
            content += '<div class="col-md-6" style="margin-bottom:10px">';
                content += data['starting_date_raw'] + ' @ <strong>' + data['metadata']['time_of_day_string'] + ' (local time)</strong><br>';
            content += '</div>';

            content += '<div class="col-md-6" style="margin-bottom:10px">';
                content += '<strong>Passing By Your Town:</strong><br>';
            content += '</div>';
            content += '<div class="col-md-6" style="margin-bottom:10px">';
                content += data['pickup_time']['date'] + ' @ <strong>' + data['pickup_time']['time'] +  ' (approximately)</strong><br>';
            content += '</div>';

            content += '<div class="col-md-6" style="margin-bottom:10px">';
                content += '<strong>Seats Remaining:</strong><br>';
            content += '</div>';
            content += '<div class="col-md-6" style="margin-bottom:10px">';
                content += data['remaining_slots'];
            content += '</div>';

        content += '</div>';

        content += '<hr>';

        if (!data['auth']['status']) {
            content += '<div class="col-md-12"><a href="/login">Login to book this ride</a></div>';
        } else {
            content += '<form method="POST" action="/bookings" id="booking_form" name="booking_request">';
                content += '<div class="row">' + this.bookListingBox(data) + '</div>';
            content += '</div>';
        }

        return content;
    };

    SearchController.Results.bookListingBox = function (data)
    {
          var content = '<input type="hidden" name="_token" value="' + SearchController.getToken() + '">';
            content += '<input type="hidden" name="fk_listing_id" value="' + data['id'] + '">';
            content +=  '<div class="col-md-6">';
                    content += '<label>Pickup location:</label><input class="form-control" id="booking_pickup_location" placeholder="Address, City, or U.S. Zip Code" value="' + $("#original_query").val() +'" name="location" disabled="disabled"/>';
            content += '</div>';
            content +=  '<div class="col-md-6">';
                content += '<label>Number of Passengers:</label><input class="form-control" id="total_people_field" placeholder="How many people are coming along?" value="' + $("#total_people").val() +'" name="total_people"/><span class="help-block hidden" id="total_people_error" style="color: #b90000;"></span>';
            content += '</div>';

            content += '<div class="col-md-12" style="margin-top:15px;"><div class="alert alert-warning"><p class="text-small">(We will not show ' + data['host'] + ' your full address until the day before the trip)</p></div><span class="help-block hidden" id="booking_additional_info_error" style="color: #b90000;"></span></div>';

            content +=  '<div class="col-md-12">';
                content += '<label>Additional info:</label><textarea name="additional_info" rows="7" cols="100" placeholder="Say hi, introduce yourself." id="booking_additional_info" class="form-control" required></textarea>';
            content += '</div>';

            content += '<div class="col-md-12" style="margin-top:15px;"><div class="alert alert-warning"><p class="text-small"><strong>DISCLAIMER:</strong><br>SeeYouInPhilly.com matches drivers with people looking to carpool. We don\'t run background checks and aren\'t responsible for any actions of the drivers or carpoolers. Get to know the other party before sharing rides! Be safe and report any suspicious activity to 911. Wear a seat belt at all times and don\'t drink and drive or ride with anyone driving under the influence of any substance.<br><br>We are in no way associated with the official Bernie Sanders campaign.</p></div></div>';

            content += '<div class="checkbox col-md-12" style="margin-left:17px;margin-right: 17px;text-align: center;"><label class=""><div class="i-check"><input class="i-check" type="checkbox" id="dog_friendly" name="dog_friendly" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div>I\'ve read and fully understood the disclaimer above and also agree to the <a href="/tos" target="_blank">terms of service</a></label></div>';
        content += '<div class="col-md-12" style="margin-top:20px">';
                content += '<span class=""><center><button class="btn btn-primary btn-lg" type="submit">Send Ride Request</button></center></span>';
            content += '</div>';

        return content;
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

    SearchController.Results.validateBooking = function (listing_data) {
        // Hide any errors from last submit
        $("#booking_additional_info_error").addClass('hidden');
        $("#total_people_error").addClass('hidden');

        var errors = {};
        var total_people = $("#total_people_field").val();

        if ($("#booking_additional_info").val().length < 50) {
            errors['booking_additional_info'] = 'Please enter at least 50 characters of additional information';
        }

        if (total_people > listing_data['remaining_slots']) {
            errors['total_people'] = 'You can\' book ' + total_people  + ' people in this ride. There\'s only ' + listing_data['remaining_slots'] + ' seats left.';
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