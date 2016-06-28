(function ($, undefined) {

    var PACKAGE_NAME = 'home';
    var HomeActions = {};

    jQuery(function($) {
        $(document).ready(function () {
            if ($("#app").attr('about') == PACKAGE_NAME) {
                var packages = {
                    SearchComponent: {
                        autocompleteElementId: "autocomplete",
                        rideFormName: "ride_search_form"
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

            HomeActions[_package]['_register'](packages[_package]);
        }
    }

    HomeActions.SearchComponent = {};

    HomeActions.SearchComponent._register = function (config){
        var searchField         = document.getElementById(config['autocompleteElementId']);
        var autocomplete        = new google.maps.places.Autocomplete(searchField, {type: ['geocode']});

        $("input[type=radio][name=ride_total_people_radio]").change(function () {
            $("#ride_total_people").val($(this).val())
        });

        $("#ride_total_people_select").change(function () {
            $("#ride_total_people").val($(this).val())
        });

        $("#ride_search_form").submit(function () {
            if (!$("#autocomplete").val().length) {
                $("#ride_search_error").html();
                $("#ride_search_error").html('<strong>Enter an address into the search field</strong>');
                return false;
            }
        });
    }

}(window.jQuery));