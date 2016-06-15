/**
 * @file
 * My Bookings
 *
 * @author Carlos Granados <granados.carlos91@gmail.com>
 */
(function ($, undefined) {

    var PACKAGE_NAME = 'my-bookings';
    var MyBookingsController = {'token':null};

    jQuery(function($) {
        $(document).ready(function () {
            var app = $("#app");
            if (app.attr('about') == PACKAGE_NAME) {
                var packages = {
                    ContactDriver: {
                        modalId: "#contact_window",
                        trigger: ".contact_driver"
                    }
                };

                registerEvents(packages);
            }
        });
    });

    function registerEvents(packages) {
        for (var _package in packages) {
            if (!packages.hasOwnProperty(_package)) continue;

            MyBookingsController[_package]['_register'](packages[_package]);
        }
    }

    MyBookingsController.ContactDriver = {};

    MyBookingsController.ContactDriver._register = function (config)
    {
        $(config['trigger']).click(function () {
            MyBookingsController.ContactDriver.handle($(this).attr('about'))
        });
    };

    MyBookingsController.ContactDriver.handle = function (listing_id)
    {
        $.mobilizerAPI().get_contact_email(listing_id, function (data) {
            MyBookingsController.ContactDriver.modal(data);
        });
    };

    MyBookingsController.ContactDriver.modal = function (contactInfo)
    {
        $("#driver_contact_email").val(contactInfo['email']);
        $("#driver_contact_name").html(contactInfo['first_name']);
        $("#contact_window").modal('toggle');
    };

}(window.jQuery));