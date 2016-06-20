/**
 * @file
 * My Bookings
 *
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
(function ($, undefined) {

    var PACKAGE_NAME = 'my-bookings';
    var MyBookings = {'token':null};

    jQuery(function($) {
        $(document).ready(function () {
            var app = $("#app");
            if (app.attr('about') == PACKAGE_NAME) {
                var packages = {
                    ContactDriver: {
                        modalId: "#contact_window",
                        trigger: ".contact_driver"
                    },
                    CancelBooking: {
                        modalId: "#cancel_window",
                        trigger: ".cancel_trip",
                        confirm: ".confirm_cancel",
                        host_name: "#cancel_window_host_name"
                    }
                };

                MyBookings.token    = app.attr('data-token');
                MyBookings.api      = $.mobilizerAPI({token:MyBookings.getToken()});
                registerEvents(packages);
            }
        });
    });

    function registerEvents(packages) {
        for (var _package in packages) {
            if (!packages.hasOwnProperty(_package)) continue;

            MyBookings[_package]['_register'](packages[_package]);
        }
    }

    MyBookings.ContactDriver    = {};
    MyBookings.CancelBooking    = {};
    MyBookings.CancelBooking.config = {};
    MyBookings.getToken = function () {
        return this.token;
    };

    MyBookings.ContactDriver._register = function (config)
    {
        $(config['trigger']).click(function () {
            MyBookings.ContactDriver.handle($(this).attr('about'))
        });
    };

    MyBookings.CancelBooking._register = function (config)
    {
        MyBookings.CancelBooking.config = config;

        $(config['trigger']).click(function () {
            MyBookings.CancelBooking.handle($(this).attr('about'), $(this).attr('data-text'));
        });

        $(config['confirm']).click(function () {
            $(this).html('CANCELLING...');
            MyBookings.api.cancel_trip($(this).attr('about'), function () {
                location.reload();
            });
        });
    };

    MyBookings.CancelBooking.handle = function (booking_id, host_name)
    {
        $(MyBookings.CancelBooking.config['host_name']).html(host_name);
        $(MyBookings.CancelBooking.config['confirm']).attr('about', booking_id);
        $(MyBookings.CancelBooking.config['modalId']).modal('toggle');
    };

    MyBookings.ContactDriver.handle = function (listing_id)
    {
        MyBookings.api.get_listing_contact_info(listing_id, function (data) {
            MyBookings.ContactDriver.modal(data);
        });
    };

    MyBookings.ContactDriver.modal = function (contactInfo)
    {
        $("#driver_contact_email").val(contactInfo['email']);
        $("#driver_contact_name").html(contactInfo['first_name']);
        $("#driver_contact_email_2").html(contactInfo['email']);
        $("#contact_window").modal('toggle');
    };

}(window.jQuery));