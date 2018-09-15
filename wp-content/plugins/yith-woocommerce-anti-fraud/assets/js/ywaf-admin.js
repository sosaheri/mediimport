jQuery(function ($) {

    $('.ywaf-start-check, .ywaf-repeat-check').click(function () {

        var container = $('.ywaf-risk-container'),
            repeat = ($(this).is('.ywaf-repeat-check')) ? 'true' : 'false';

        if (container.is('.processing')) {
            return false;
        }

        container.addClass('processing');

        container.block({
            message   : null,
            overlayCSS: {
                background: '#fff',
                opacity   : 0.6
            }
        });

        $.ajax({
            type    : 'POST',
            url     : ywaf_ajax_url,
            data    : {
                repeat: repeat
            },
            success : function (response) {

                if (response.status == 'success') {

                    if (response.redirect.indexOf("https://") != -1 || response.redirect.indexOf("http://") != -1) {
                        window.location = response.redirect;
                    } else {
                        window.location = decodeURI(response.redirect);
                    }

                } else {

                    container.removeClass('processing').unblock();
                    window.alert(response.error);

                }

            },
            dataType: 'json'
        });

        return false;

    });

});