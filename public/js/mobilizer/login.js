(function ($, undefined) {

    jQuery(function($) {
        $(document).ready(function () {
            $("#sign_up_form").submit(function (e) {
                console.dir('test');
                if (!$("#disclaimer_accept").prop('checked')) {
                    e.preventDefault();
                    $("#accept_tos").html('You must accept the disclaimer above to sign up.');
                    return false;
                }
            });
        });
    });

}(window.jQuery));