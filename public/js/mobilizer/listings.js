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
                        mapId: 'map-canvas',
                        deactivate_listing_button: '#deactivate_listing',
                        deactivate_listing_confirm: '#deactivate_confirm'
                    },
                    EditWindow: {
                        modal: '#edit_window',
                        mapId: 'map-canvas',
                        trigger: '.listing_result',
                        listings_holder: '#listings-list',
                        save_edits: '#save_edits',
                        user_form: '#edit_listing_form',
                        character_count_place_holder: "#additional_info_character_count",
                        additional_info_text_field: "#listing_additional_info",
                        deactivate_listing_button: '#deactivate_listing',
                        deactivate_listing_confirm: '#deactivate_confirm',
                        deactivate_confirm_party_name: '#deactivate_party_name',
                        deactivate_cancel: '#deactivate_cancel',
                        deactivate_confirm_button: '#deactivate_confirm_button'
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
            MyListings[_package]['config'] = packages[_package];

            // Boot package
            MyListings[_package]['boot'](packages[_package]);
        }
    }

    MyListings.dataStore    = {};
    MyListings.MainDisplay  = {};
    MyListings.EditWindow   = {};
    MyListings.Validators   = {};
    MyListings.getToken     = function () {
        return this.token;
    };

    MyListings.currentItem      = null;

    MyListings.getCurrentItem   = function (path) {
        if (typeof path == 'undefined') {
            return this.currentItem;
        }

        var paths = path.split('.');
        var data  = this.currentItem;

        for (var i = 0; i < paths.length; i++)
        {
            data = data[paths[i]];
        }

        return data;
    };

    MyListings.setCurrentItem = function (currentItem) {
        this.currentItem = currentItem;
        return currentItem;
    };

    MyListings.MainDisplay.boot = function (config)
    {
        var modal           = $("#edit_window");
        var destLat         = 39.901096;
        var destLong        = -75.171874;
        var latlng          = new google.maps.LatLng(destLat, destLong);
        var deactivate_listing  = $(config['deactivate_listing_button']);
        var deactivate_confirm  = $(config['deactivate_listing_confirm']);
        var myOptions       = {
            zoom: 16,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: false
        };

        MyListings.EditWindow.map = new google.maps.Map(document.getElementById(config['mapId']), myOptions);

        var map             = MyListings.EditWindow.map;
        var tripRoute       = new google.maps.Polyline({
            strokeColor: '#0066cc',
            strokeOpacity: 1,
            strokeWeight: 4,
            fillColor: '#3399ff',
            map: map
        });

        $(config['listings_holder']).on('click', config['trigger'], function () {
            var key     = $(this).attr('data-key');

            MyListings.EditWindow.clearValidationErrors();

            MyListings.setCurrentItem(MyListings.dataStore[key]);

            MyListings.EditWindow.fill(MyListings.getCurrentItem());

            modal.modal('toggle');

            // Add map info
            modal.on('shown.bs.modal', function() {
                // Call this so the map doesn't show as a big grey box
                google.maps.event.trigger(map, "resize");

                // Decode overview path and draw to map
                var polyline            = google.maps.geometry.encoding.decodePath(MyListings.getCurrentItem('route.overview_path'));
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
        var submit_button       = $(config['save_edits']);
        var user_form           = $(config['user_form']);
        var characterCount      = $(config['character_count_place_holder']);
        var additional_info     = $(config['additional_info_text_field']);
        var deactivate_listing  = $(config['deactivate_listing_button']);
        var deactivate_confirm  = $(config['deactivate_listing_confirm']);
        var deactivate_cancel   = $(config['deactivate_cancel']);
        var modal               = $(config['modal']);

        var deactivate_confirm_button       = $(config['deactivate_confirm_button']);
        var deactivate_confirm_party_name   = $(config['deactivate_confirm_party_name']);

        // Character count for textbox
        additional_info.keydown(function () {
            var cs = $(this).val().length;
            characterCount.text(cs);
        });

        additional_info.keyup(function () {
            var cs = $(this).val().length;
            characterCount.text(cs);
        });

        additional_info.on('paste', function () {
            var cs = $(this).val().length;
            characterCount.text(cs);
        });

        // Button in edit form
        submit_button.click(function () {
            // Get data from form:
            var data    = $.deparam($(user_form).serialize());

            try {
                MyListings.EditWindow.clearValidationErrors();
                MyListings.EditWindow.validateForm(data);
                // Validation passed!
                submit_button.html('SAVING...');
                submit_button.removeClass('btn-primary');
                submit_button.addClass('btn-warning');

                MyListings.api.edit_listing(MyListings.getCurrentItem('id'), data, function (response) {
                    submit_button.html('SAVE');
                    submit_button.removeClass('bnt-warning');
                    submit_button.addClass('btn-primary');
                });

            } catch (validationErrors) {
                MyListings.EditWindow.handleFormValidationFail(validationErrors);
            }
        });

        deactivate_listing.click(function () {
            $(this).hide();

            deactivate_confirm_party_name.html(MyListings.getCurrentItem('party_name'));
            deactivate_confirm.slideDown();
        });

        deactivate_cancel.click(function () {
            deactivate_confirm.slideUp();
            deactivate_listing.show();
        });

        deactivate_confirm_button.click(function () {
            MyListings.api.edit_listing(MyListings.getCurrentItem('id'), {active:0}, function () {
                modal.modal('toggle');
                MyListings.MainDisplay.load();
            });
        });
    };

    MyListings.EditWindow.clearValidationErrors = function ()
    {
        var validators = MyListings.Validators.edit;

        for (var field in validators) {
            if (!validators.hasOwnProperty(field)) continue;

            var selector = $("#" + field + "_error");

            selector.html('');
            selector.addClass('hidden');
        }
    };

    MyListings.EditWindow.handleFormValidationFail = function (errors)
    {
        for (var field in errors) {
            if (!errors.hasOwnProperty(field)) continue;

            var selector = $('#' + field + "_error");
            selector.html(errors[field]);
            selector.removeClass('hidden');
        }
    };

    MyListings.Validators.edit = {
        'max_occupants': function (value)
        {
            var slots_booked = MyListings.getCurrentItem('slots_booked');

            if (isNaN(value)) {
                throw "maximum number of passengers is supposed to be a number, you entered \"" + value + "\"";
            } else if(value == 0) {
                throw "maximum number of passengers must be greater than zero.";
            } else if (slots_booked > value) {
                throw "You already have accepted " + slots_booked + " seats in this ride. You cannot edit this field to " + value + " unless you cancel enough bookings.";
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

    MyListings.EditWindow.validateForm = function (data)
    {
        var required    = {party_name: 'A party name is required', additional_info: 'Additional information is required', max_occupants: "A number of passengers is required"};
        var messageBag  = {};
        var validators  = MyListings.Validators.edit;

        for (var field in required) {
            if (!required.hasOwnProperty(field)) continue;

            if (!field in data || !$.trim(data[field]).length) {
                messageBag.push(required[field]);
                continue;
            }

            if (field in validators) {
                try {
                    validators[field](data[field]);
                } catch (message) {
                    messageBag[field] = message;
                }
            }
        }

        if (!$.isEmptyObject(messageBag)) {
            throw messageBag;
        }
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

    MyListings.EditWindow.fill = function (data)
    {
        $("#listing_party_name").val(data['party_name']);
        $("#listing_max_occupants").val(data['max_occupants']);
        $("#listing_additional_info").val(data['additional_info']);
        $("#listing_date_leaving").html(data['starting_date']);
        $("#listing_time_of_day").html(data['time_of_day']);
        $("#listing_date_returning").html(data['ending_date']);
        $("#listing_route_name").html('via ' + data['route']['name']);
        $("#additional_info_character_count").html(data['additional_info'].length);

        $("#deactivate_confirm").hide();
        $("#deactivate_listing").show();
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