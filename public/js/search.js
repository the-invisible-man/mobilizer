(function ($, undefined) {

    var PACKAGE_NAME = 'search';
    var SearchActions = {};

    jQuery(function($) {
        $(document).ready(function () {
            if ($("#app").attr('about') == PACKAGE_NAME) {
                var packages = {
                    SearchComponent: {
                        autocompleteElementId: "autocomplete"
                    },
                    ResultController: {
                        resultElementId: "listing_result"
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

            SearchActions[_package]['_register'](packages[_package]);
        }
    }

    SearchActions.SearchComponent = {};
    SearchActions.ResultController = {};

    SearchActions.SearchComponent._register = function (config){
        var autocomplete        = new google.maps.places.Autocomplete((document.getElementById(config['autocompleteElementId'])), {type: ['geocode']});

    };

    SearchActions.ResultController._register = function (config) {

        // Bind actions of each result
        $(document.getElementById(config['resultElementId'])).click(function () {

        });
    };

}(window.jQuery));