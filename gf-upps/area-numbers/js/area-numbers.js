jQuery(document).bind("gform_post_render", function (event, form_id) {
    jQuery(function ($) {
        const getLocal = $('html').attr('lang');
        const phone_id = area_data.elements["input_" + form_id][0];
        let area_code = area_data.elements["input_" + form_id][1];
        var main_pattern = '';
        var old_title = '';
        let errorAfter = false;
        const domain = window.location.hostname;
        const paternRegex = /[^9+()-]/;

        const createPattern = (unknown = false) => {
            if (unknown === true) {
                const pattern = '+999999999';
                return pattern.replace(new RegExp("[0-9]", "g"), "9");
            }
            const titles = $('.iti__selected-flag').attr('title').split('+');
            const pattern = '+' + titles[1] + ' ' + $(phone_id).attr('placeholder');
            $('#validation_phone').remove();
            errorAfter = false;
            return pattern.replace(new RegExp("[0-9]", "g"), "9");
        }

        const updatePhone = (title, paste = false) => {
            main_pattern = createPattern();
            newValue = title.split('+');

            if (window.location.hostname === "warsawexpo.eu") {
                const phoneNewVal = $(phone_id).val();

                if (phoneNewVal.startsWith('+') || phoneNewVal.length > 4) {
                    $(phone_id).attr('value', phoneNewVal);
                }
            }

            if (!$(phone_id).val().startsWith('+') || $(phone_id).val().length > 4 || paste === true) {
                $(phone_id).val('+' + newValue[1] + ' ');
            }

            if (main_pattern[$(phone_id).val().length] === '(') {
                $(phone_id).val($(phone_id).val() + '(');
            }
        }

        const observer = new MutationObserver(function (mutationsList, observer) {
            for (var mutation of mutationsList) {
                if (mutation.type === 'attributes') {
                    if (mutation.attributeName === 'title') {
                        if ($(mutation.target).attr('title') == 'Unknown') {
                            main_pattern = createPattern(true);
                            old_title = 'Unknown';
                        } else if ($(phone_id).val().length < 1) {
                            updatePhone($(mutation.target).attr('title'));
                        } else {
                            if (old_title != $(mutation.target).attr('title')) {
                                updatePhone($(mutation.target).attr('title'));
                                old_title = $(mutation.target).attr('title');
                                main_pattern = createPattern();
                            }
                        }
                    } else if (mutation.attributeName === 'placeholder') {
                        if (paternRegex.test(main_pattern)) {
                            main_pattern = createPattern();
                        }
                    }
                }
            }
        });

        const succesCountryIp = (countryCode) => {

            if (window.location.hostname === "warsawexpo.eu") {
                if ($(phone_id).val().startsWith('+') && $(phone_id).val().length > 4) {
                    $(phone_id).prop('disabled', false);
                    return;
                }
            }

            let options = {
                initialCountry: countryCode,
                utilsScript: "https://" + domain + "/wp-content/plugins/PWElements/gf-upps/area-numbers/js/utils.js",
                autoPlaceholder: "aggressive",
            }

            $(phone_id).intlTelInput(options);

            var targetUL = document.querySelector('.iti__selected-flag');
            var targetInput = document.querySelector('.ginput_container_phone input');

            updatePhone($(targetUL).attr('title'));
            old_title = $(targetUL).attr('title')
            var config = { attributes: true, attributeFilter: ['title', 'placeholder'] };

            observer.observe(targetUL, config);
            observer.observe(targetInput, config);

            setTimeout(function () {
                main_pattern = createPattern();
                $(phone_id).prop('disabled', false);
            }, 200);
        }

        const errorPhoneCheck = (event) => {
            event.preventDefault();
            const phoneInput = $(event.target).parent().parent().find(phone_id);
            if (phoneInput.val().length >= 8) {
                $(event.target).parent().parent().submit();
            } else {
                if (errorAfter == false) {
                    let errorMessage = 'Wypełnić według wzoru ' + main_pattern;
                    if (getLocal != 'pl-PL') {
                        errorMessage = 'Fill in by pattern ' + main_pattern;
                    }

                    const errorDiv = $('<span>')
                        .attr('id', 'validation_phone')
                        .addClass('gfield_description validation_message gfield_validation_message')
                        .text(errorMessage);

                    $(phone_id).parent().parent().after(errorDiv);

                    errorAfter = true;
                }
            }
        }

        $(phone_id).on('input', function () {
            if (window.location.hostname !== "warsawexpo.eu") {
                if (!$(this).val().startsWith('+')) {
                    const targetUL = document.querySelector('.iti__selected-flag');
                    updatePhone($(targetUL).attr('title'), $(this).val());
                }
            }
        });

        $(phone_id).on('keypress', function (event) {
            const good_char = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];
            const pressedKey = event.originalEvent.key;
            if (!good_char.includes(pressedKey) || main_pattern[$(this).val().length] === undefined) {
                event.preventDefault();
            } else {
                if (main_pattern[$(this).val().length] != '9' && (main_pattern[$(this).val().length] == ' ' || main_pattern[$(this).val().length] == '(' || main_pattern[$(this).val().length] == ')' || main_pattern[$(this).val().length] == '-')) {
                    $(this).val($(this).val() + main_pattern[$(this).val().length]);
                }
                if (main_pattern[$(this).val().length] != '9' && (main_pattern[$(this).val().length] == ' ' || main_pattern[$(this).val().length] == '(' || main_pattern[$(this).val().length] == ')' || main_pattern[$(this).val().length] == '-')) {
                    $(this).val($(this).val() + main_pattern[$(this).val().length]);
                }
                if (main_pattern[$(this).val().length] != '9' && (main_pattern[$(this).val().length] == ' ' || main_pattern[$(this).val().length] == '(' || main_pattern[$(this).val().length] == ')' || main_pattern[$(this).val().length] == '-')) {
                    $(this).val($(this).val() + main_pattern[$(this).val().length]);
                }
            }
        });

        $(phone_id).on('paste', function (event) {
            const pastedText = (event.originalEvent.clipboardData || window.clipboardData).getData('text');
            if (/[^+\d()]/u.test(pastedText)) {
            };
        });

        $('form').has('.gfield_visibility_visible ' + phone_id).find('.gform_button').on('click', function (event) {
            if (window.location.hostname !== "warsawexpo.eu" && $(this).closest('form').find('.gfield--type-phone').attr('data-conditional-logic') != 'hidden') {
                errorPhoneCheck(event);
            }
        });

        // $('form').on('keypress', phone_id, function (event) {
        //     if (event.which === 13) {
        //         errorPhoneCheck(event);
        //     }
        // });

        $(phone_id).attr('type', 'tel');

        if (area_code.toLowerCase() == 'def' || area_code.toLowerCase() == '') {
            fetch("https://ipapi.co/json")
                .then(function (res) { return res.json(); })
                .then(function (data) { succesCountryIp(data.country_code) })
                .catch(function () { console.log('error'); succesCountryIp('PL') });
        } else {
            succesCountryIp(area_code);
        }
    });
})