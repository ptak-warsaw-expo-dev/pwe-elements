document.addEventListener("DOMContentLoaded", function() {
    // Lazy load for iframes
    // The video will load when you hover over it
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                // Get the iframe and set the src from data-src
                var PWEIframe = entry.target;
                if (PWEIframe.getAttribute('data-src')) {
                    PWEIframe.src = PWEIframe.getAttribute('data-src');
                    // PWEIframe.removeAttribute('data-src'); // Remove data-src
                }
                // Unobserving this item
                observer.unobserve(PWEIframe);
            }
        });
    },

    {
        rootMargin: '100px 0px', // Increase the watchable area
        threshold: 0.1
    });

    // Start observing iframes
    document.querySelectorAll('.pwe-video-item iframe[data-src]').forEach(function(PWEIframe) {
        observer.observe(PWEIframe);
    });
});


/* Show more/less */
jQuery(function ($) {
    let hiddenContentPWE = true;

    // słownik tłumaczeń
    const translations = {
        "pl_PL": {
            more: "więcej...",
            hide: "ukryj..."
        },
        "en_EN": {
            more: "more...",
            hide: "hide..."
        },
        "de_DE": {
            more: "mehr...",
            hide: "verbergen..."
        }
    };

    const locale = translations[pweScriptData.locale] || translations["en_EN"];

    $(".pwe-see-more").click(function (event) {
        let currentText = $(event.target).text();

        if (currentText === locale.more) {
            $(event.target).text(locale.hide);
        } else {
            $(event.target).text(locale.more);
        }

        hiddenContentPWE = !hiddenContentPWE;
        $(event.target).prev().slideToggle();
    });
});




/* ROZWIJANE ZGODY */
jQuery('document').ready(function ($) {
    $('.show-consent').on('click touch', function () {
        // $(this).parents( "li" ).find('.gfield_consent_description').toggle( "slow" );
        $(this).parents( "fieldset" ).find('.gfield_consent_description').toggle( "slow" );
    });
});





// function getLocationPath() {
//     const urlParams = new URLSearchParams(window.location.search);
//     const utmSource = urlParams.get('utm_source');

//     if (utmSource === 'byli') {
//         return 'vip';
//     } else if (utmSource === 'premium') {
//         return 'platinum';
//     } else {
//         let urlPath = window.location.pathname;

//         if (urlPath.startsWith("/")) {
//             urlPath = urlPath.substring(1);
//         }
//         if (urlPath.endsWith("/")) {
//             urlPath = urlPath.slice(0, -1);
//         }

//         return urlPath.length > 0 ? urlPath : "header";
//     }
// }

// function setLocationToForm() {
//     const locationInput = document.querySelector(".location input");
//     if (locationInput) {
//         const locationPath = getLocationPath();
//         locationInput.value = locationPath;
//         localStorage.setItem("user_location", locationPath);
//     }
// }

// window.onload = function () {
//     setLocationToForm();
// };




