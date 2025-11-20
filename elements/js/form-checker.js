function captchaTrue() {
    jQuery(document).ready(function ($) {
        console.log($('html').attr('lang'));
        const testEmail = () => {
            emailTarget = $('input[type="email"]').val();
            if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailTarget)) {
                $('.email-error').html('').css('background-color', 'transparent');
                return true;
            } else {
                if (inner.locale == 'pl_PL') {
                    $('.email-error').text('Nieprawidłowy adres email');
                } else {
                    $('.email-error').text('Wrong email adress');
                }
                return false;
            }
        };

        const testTel = () => {
            telTarget = $('input[type="tel"]').val();
            if (/^[0-9+\(\)\s-]*$/.test(telTarget) && telTarget.length > 8) {
                $('.phone-error').html('').css('background-color', 'transparent');
                return true;
            } else {
                if (inner.locale == 'pl_PL') {
                    $('.phone-error').text('Niepoprawny telefon');
                } else {
                    $('.phone-error').text('Wrong phone number');
                }
                return false;
            }
        };

        const testCons = () => {
            const consTarget = $('input[name="consent"]');
            if (consTarget.is(':checked')) {
                $('.cons-error').html('').css('background-color', 'transparent');
                return true;
            } else {
                if (inner.locale == 'pl_PL') {
                    $('.cons-error').text('Obowiązkowe');
                } else {
                    $('.cons-error').text('Mandatory');
                }
                return false;
            }
        };

        const uniqueCheck = (email_id, phone_id, email_value, phone_value, form_id) => {
            var baseUrl = window.location.origin
            $.ajax({
                type: 'POST',
                url: baseUrl + '/wp-content/plugins/PWElements/gf-upps/gf-email-check/gf-email-check.php',
                data: { email_id: email_id, phone_id: phone_id, email_value: email_value, phone_value: phone_value, form_id: form_id },
                dataType: 'json',
                success: function (response) {
                    if (response['exists'] == false) {
                        $('#xForm').find('form[id="registration"]').submit();
                    } else {
                        Object.keys(response).forEach(function (key) {
                            if (response[key]) {
                                if (inner.locale == 'pl_PL') {
                                    if (key == 'phone') {
                                        key = 'telefon';
                                        console.log(key);
                                    }
                                    $('.' + key + '-error').text(key + ' został już użyty');
                                } else {
                                    $('.' + key + '-error').text(key + ' was already used');
                                }
                            }
                        });
                    }
                },
                error: function (xhr, status, error, response) {
                    var errorMessage = "Wystąpił błąd podczas sprawdzania emaila. " + error + '  ' + status;

                    console.error(errorMessage);
                }
            });
        }
        const testerTel = testTel(inner.form_id);
        const testerEmail = testEmail(inner.form_id);
        const testerCons = testCons(inner.form_id);

        if (testerTel && testerEmail && testerCons) {
            email_id = inner.email_id;
            phone_id = inner.phone_id;
            email_value = $('input[type="email"]').val();
            phone_value = $('input[type="tel"]').val();
            form_id = inner.form_id;
            uniqueCheck(email_id, phone_id, email_value, phone_value, form_id);
        }
    });
}

document.querySelector('button[name="step-1-submit"]').addEventListener('click', function (event) {
    event.preventDefault();
});

function onSubmit(token) {
    captchaTrue();
}

jQuery(document).ready(function ($) {
    $('.consent-text').on('click', function () {
        $('.consent-input').prop('checked', true);
    });

    const phone_id = inner.elements;
    let area_code = 'def';
    var main_pattern = '';
    var old_title = '';
    let errorAfter = false;

    const createPattern = (unknown = false) => {
        if (unknown === true) {
            const pattern = '+99 999 999 999';
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

        if (!$(phone_id).val().startsWith('+') || $(phone_id).val().length > 4 || paste === true) {
            $(phone_id).val('+' + newValue[1] + ' ');
        }

        if (main_pattern[$(phone_id).val().length] === '(') {
            $(phone_id).val($(phone_id).val() + '(');
        }
    }

    const observer = new MutationObserver(function (mutationsList, observer) {
        for (var mutation of mutationsList) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'title') {
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
            }
        }
    });

    const succesCountryIp = (countryCode) => {
        let options = {
            initialCountry: countryCode,
            utilsScript: "https://cleanexpo.pl/wp-content/plugins/PWElements/gf-upps/area-numbers/js/utils.js",
            autoPlaceholder: "aggressive",
        }

        $(phone_id).intlTelInput(options);

        var targetUL = document.querySelector('.iti__selected-flag');

        updatePhone($(targetUL).attr('title'));
        old_title = $(targetUL).attr('title')
        var config = { attributes: true };
        observer.observe(targetUL, config);
        setTimeout(function () {
            main_pattern = createPattern();
            $(phone_id).prop('disabled', false);
        }, 500);
    }

    const errorPhoneCheck = (event) => {
        event.preventDefault();
        const phoneInput = $(event.target).parent().parent().find(phone_id);
        if (phoneInput.val().length >= main_pattern.length) {
            $(event.target).parent().parent().submit();
        } else {
            if (errorAfter == false) {
                const errorDiv = $('<span>')
                    .attr('id', 'validation_phone')
                    .addClass('gfield_description validation_message gfield_validation_message')
                    .text('Wypełnić według wzoru' + main_pattern);

                $(phone_id).parent().parent().after(errorDiv);

                errorAfter = true;
            }
        }
    }

    $(phone_id).on('input', function () {
        if (!$(this).val().startsWith('+')) {
            const targetUL = document.querySelector('.iti__selected-flag');
            updatePhone($(targetUL).attr('title'), $(this).val());
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

    $('form').has(phone_id).find('.gform_button').on('click', function (event) {
        errorPhoneCheck(event);
    });

    $('form').on('keypress', phone_id, function (event) {
        if (event.which === 13) {
            errorPhoneCheck(event);
        }
    });

    if (area_code.toLowerCase() == 'def' || area_code.toLowerCase() == '') {
        fetch("https://ipapi.co/json")
            .then(function (res) { return res.json(); })
            .then(function (data) { succesCountryIp(data.country_code) })
            .catch(function () { console.error('error'); succesCountryIp('PL') });
    } else {
        succesCountryIp(area_code);
    }
});