(function($) {
    wp.customize('custom_login_background_color', function(value) {
        value.bind(function(newval) {
            $('body.login').css('background-color', newval);
        });
    });
    wp.customize('custom_login_logo_url', function(value) {
        value.bind(function(newval) {
            $('body.login div#login h1 a').css('background-image', 'url(' + newval + ')');
        });
    });
    wp.customize('custom_login_font_family', function(value) {
        value.bind(function(newval) {
            $('body.login').css('font-family', newval);
        });
    });
    wp.customize('custom_login_font_size', function(value) {
        value.bind(function(newval) {
            $('body.login').css('font-size', newval);
        });
    });
    wp.customize('custom_login_padding', function(value) {
        value.bind(function(newval) {
            $('body.login div#login').css('padding', newval);
        });
    });
    wp.customize('custom_login_margin', function(value) {
        value.bind(function(newval) {
            $('body.login div#login').css('margin', newval);
        });
    });
    wp.customize('custom_login_border_radius', function(value) {
        value.bind(function(newval) {
            $('.login form').css('border-radius', newval);
        });
    });
    wp.customize('custom_login_box_shadow', function(value) {
        value.bind(function(newval) {
            $('.login form').css('box-shadow', newval);
        });
    });
    wp.customize('custom_login_custom_css', function(value) {
        value.bind(function(newval) {
            $('#custom-login-custom-css').remove();
            $('head').append('<style id="custom-login-custom-css">' + newval + '</style>');
        });
    });
    wp.customize('custom_login_custom_message', function(value) {
        value.bind(function(newval) {
            $('.custom-message').html(newval);
        });
    });
    wp.customize('custom_login_enable_recaptcha', function(value) {
        value.bind(function(newval) {
            if (newval) {
                $('.g-recaptcha').show();
            } else {
                $('.g-recaptcha').hide();
            }
        });
    });
})(jQuery);

