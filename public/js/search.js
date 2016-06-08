(function ($, undefined) {

    var PACKAGE_NAME = 'search';
    var SearchController = {};

    jQuery(function($) {
        $(document).ready(function () {
            if ($("#app").attr('about') == PACKAGE_NAME) {
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

        // Add autocomplete
        new google.maps.places.Autocomplete((document.getElementById('booking_pickup_location')), {type: ['geocode']});
    };

    SearchController.Results.ride = function (data) {
        console.dir(data);
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
            content += '<div class="row">' + SearchController.Results.bookListingBox(data) + '</div>';
        }

        return content;
    };

    SearchController.Results.bookListingBox = function (data) {
        var content = '<form>';
                content +=  '<div class="col-md-6">';
                        content += '<label>Pickup location:</label><input class="form-control" id="booking_pickup_location" placeholder="Address, City, or U.S. Zip Code" value="' + $("#original_query").val() +'" name="location"/>';
                content += '</div>';
                content +=  '<div class="col-md-6">';
                    content += '<label>Number of Passengers:</label><input class="form-control" id="booking_pickup_location" placeholder="How many people are coming along?" value="' + $("#total_people").val() +'" name="location"/>';
                content += '</div>';

                content += '<div class="col-md-12" style="margin-top:15px;"><div class="alert alert-warning"><p class="text-small">(We will not show ' + data['host'] + ' your full address until the day before the trip)</p></div></div>';

                content +=  '<div class="col-md-12">';
                    content += '<label>Additional info:</label><textarea name="additional_info" rows="7" cols="100" placeholder="Say hi, introduce yourself." id="InputMessage" class="form-control" required></textarea>';
                content += '</div>';

                content += '<div class="col-md-12" style="margin-top:20px">';
                    content += '<span class=""><center><button class="btn btn-primary btn-lg" type="submit">Send Ride Request</button></center></span>';
                content += '</div>';
            content += '</form>';
        return content;
    };

    SearchController.Results.housing = function (data) {

    };

}(window.jQuery));