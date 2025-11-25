jQuery(document).ready(function ($) {
    window.yourFunction = function (toSHow, toHide) {
        const hideElem = ".vc_shortcode-param[data-vc-shortcode-param-name='" + toHide + "']";
        const showElem = ".vc_shortcode-param[data-vc-shortcode-param-name='" + toSHow + "']";
        if ($(showElem).hasClass('vc_dependent-hidden')) {
            $(showElem).removeClass('vc_dependent-hidden')
        }
        $(showElem).removeClass('pwe_dependent-hidden');
        $(hideElem).addClass('pwe_dependent-hidden');
        $(hideElem).find('input').val('');
        $(hideElem).find('select').find('option[selected="selected"]').removeAttr('selected');
    };

    window.hideEmptyElem = function () {
        $('.pwe_dependent-hidden').each(function () {
            if ($(this).find('input').val() != '') {
                $(this).removeClass('pwe_dependent-hidden');
            }
        });
    }
    
    //     // setTimeout(function () {
    //     //     $('.vc_control-btn-edit[title="Edit PWE Elements"]').on('click', function () {
    //     //         setTimeout(function () {
    //     //             console.log($('.vc_shortcode-param[data-vc-shortcode-param-name$="hidden"]'));
    //     //             $('.vc_shortcode-param[data-vc-shortcode-param-name$="hidden"]').each(function () {
    //     //                 if ($(this).find('input').val() != '') {
    //     //                     const colorHex = ".vc_shortcode-param[data-vc-shortcode-param-name='" + $(this).attr('data-vc-shortcode-param-name') + "']";
    //     //                     const ColorPalet = str_replace('_manual_hidden', '', colorHex);
    //     //                     $(colorHex).css('display', 'flex');
    //     //                     $(ColorPalet).css('display', 'none');
    //     //                 }
    //     //             });
    //     //         }, 1000);
    //     //     });
    //     // }, 1000);

});







