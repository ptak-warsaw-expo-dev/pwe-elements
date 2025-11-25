const accent_color = data_js.accent_color;


document.addEventListener('DOMContentLoaded', function () {

    // // Header button <--------------------------------------------------------------------------<
    // const mobileMenuButton = document.querySelector('.mobile-menu-button');
    // const htmlLang = document.documentElement.lang;

    // if (mobileMenuButton) {
    //     const pweForm = document.querySelector('#pweForm');
    //     const registerPage = htmlLang === 'pl-PL' ? '/rejestracja/' : '/en/registration/';
    //     const hrefValue = pweForm ? '#pweForm' : registerPage;
    //     const textValue = htmlLang === 'pl-PL' ? 'WEŹ UDZIAŁ' : 'TAKE A PART';
    //     const participateButton = '<a href="' + hrefValue + '" class="participate-button" style="background-color:' + data_js['main2_color'] + ';">' + textValue + '</a>';

    //     mobileMenuButton.insertAdjacentHTML('beforebegin', participateButton);

    //     document.querySelector('.participate-button').addEventListener('click', function (event) {
    //         if (pweForm) {
    //             event.preventDefault();
    //             pweForm.scrollIntoView({ behavior: 'smooth' });
    //             history.replaceState(null, '', window.location.pathname);
    //         }
    //     });
    // }


    // // Links for menu logotype <---------------------------------------------------------------------< 
    // // Configuration for domains
    // const domainSettings = {
    //     'wiretechpoland.com': { leftWidth: '20%', rightWidth: '80%' },
    //     'labelingtechpoland.com': { leftWidth: '15%', rightWidth: '85%' },
    //     // ...add more
    // };

    // const currentDomain = window.location.hostname;
    // const settings = domainSettings[currentDomain] || { leftWidth: '65px', rightWidth: 'calc(100% - 65px)' };

    // const commonStyles = {
    //     position: 'absolute',
    //     top: '0',
    //     height: '100%',
    //     zIndex: '10'
    // };

    // // Function to add links to the logo
    // function addLinksToLogo(logoElement) {
    //     if (!logoElement) return;

    //     const existingLink = logoElement.querySelector('a');
    //     if (existingLink) {
    //         // Move all children of the <a> element to the parent (e.g., #main-logo or #mobile-logo)
    //         while (existingLink.firstChild) {
    //             logoElement.insertBefore(existingLink.firstChild, existingLink);
    //         }
    //         // Remove the empty <a> element
    //         existingLink.remove();
    //     }

    //     // Create new links
    //     const leftLink = document.createElement('a');
    //     const rightLink = document.createElement('a');

    //     const pwePageLink = htmlLang === 'pl-PL' ? 'https://warsawexpo.eu/' : 'https://warsawexpo.eu/en/';
    //     const mainPageLink = htmlLang === 'pl-PL' ? '/' : '/en/';

    //     // Style for the left link
    //     Object.assign(leftLink.style, commonStyles);
    //     leftLink.style.width = settings.leftWidth;
    //     leftLink.style.left = '0';
    //     leftLink.href = (window.innerWidth < 960 && logoElement.offsetWidth > 200) ? mainPageLink : pwePageLink;
    //     leftLink.target = '_blank';

    //     // Style for the right link
    //     Object.assign(rightLink.style, commonStyles);
    //     rightLink.style.width = settings.rightWidth;
    //     rightLink.style.right = '0';
    //     rightLink.href = mainPageLink;

    //     // Append the links to the logo
    //     logoElement.appendChild(leftLink);
    //     logoElement.appendChild(rightLink);
    // }

    // // Select the logos and add links to them
    // const mainLogo = document.querySelector('.logo-image.main-logo');
    // const mobileLogo = document.querySelector('.logo-image.mobile-logo');

    // addLinksToLogo(mainLogo);
    // addLinksToLogo(mobileLogo);





    // Mobile header <--------------------------------------------------------------------------<
    const squaresModeBgs = document.querySelectorAll('.pwe-header-background .pwe-bg-image');

    if (squaresModeBgs && squaresModeBgs.length > 0) {
        let currentIndex = 0;
        let isFirstLoop = true;

        function changeBackground() {
            // Reset all images
            squaresModeBgs.forEach((bg) => bg.classList.remove('visible'));

            // If first loop active, show all images
            if (isFirstLoop) {
                if (currentIndex === 0) {
                    squaresModeBgs[0].classList.add('visible'); // First image for 3 seconds
                    setTimeout(() => {
                        currentIndex = 1;
                        changeBackground(); // Go to second image after 3 seconds
                    }, 3000);
                } else if (currentIndex === 1) {
                    squaresModeBgs[1].classList.add('visible'); // Second image for 10 seconds
                    setTimeout(() => {
                        currentIndex = 2;
                        changeBackground(); // Go to third image after 10 seconds
                    }, 3000);
                } else if (currentIndex === 2) {
                    squaresModeBgs[2].classList.add('visible'); // Third image for 10 seconds
                    setTimeout(() => {
                        isFirstLoop = false; // Set the flag that the first loop has finished
                        currentIndex = 1; // Set to the second image at the beginning of the next loops
                        changeBackground();
                    }, 3000);
                }
            } else {
                // Next loops: only second and third images
                if (currentIndex === 1) {
                    squaresModeBgs[1].classList.add('visible'); // Second image for 10 seconds
                    setTimeout(() => {
                        currentIndex = 2;
                        changeBackground();
                    }, 3000);
                } else if (currentIndex === 2) {
                    squaresModeBgs[2].classList.add('visible'); // Third image for 10 seconds
                    setTimeout(() => {
                        currentIndex = 1;
                        changeBackground();
                    }, 3000);
                }
            }
        }

        changeBackground();
    }



    let postParagraphs = document.querySelectorAll('.post-template-default p');
    postParagraphs.forEach(function(paragraph) {
        if (paragraph.innerHTML.includes("Obserwuj nas w Wiadomościach Google")) {
            paragraph.innerHTML = "Dziękujemy, że przeczytałaś/eś nasz artykuł do końca.";
        } else if (paragraph.innerHTML.includes("Follow us on Google News")) {
            paragraph.innerHTML = "Thank you for reading our article to the end.";
        }
    });

});

// // Funkcja do ustawiania cookie
// function setCookie(name, value, hours) {
//     var expires = "";
//     if (hours) {
//         var date = new Date();
//         date.setTime(date.getTime() + (hours * 60 * 60 * 1000));
//         expires = "; expires=" + date.toUTCString();
//     }
//     document.cookie = name + "=" + (value || "") + expires + "; path=/";
// }
// // Funkcja do pobierania parametrów z URL
// function getUTMParameters() {
//     var params = ['utm_source', 'utm_medium', 'utm_campaign'];
//     var utmValues = params.map(function (param) {
//         var value = new URLSearchParams(window.location.search).get(param);
//         return value ? param + '=' + value : '';
//     }).filter(Boolean).join('&');
//     // console.log('Zbierane wartości UTM:', utmValues); // Dodane do debugowania
//     return utmValues;
// }
// // Zapisanie UTM jako jednego ciągu w cookies
// var utmParams = getUTMParameters();
// // console.log('Zapisane w cookies:', utmParams); // Dodane do debugowania
// if (utmParams) {
//     setCookie('utm_params', utmParams, 24);
// }

// // Funkcja do odczytywania cookie
// function getCookie(name) {
//     var nameEQ = name + "=";
//     var ca = document.cookie.split(';');
//     for (var i = 0; i < ca.length; i++) {
//         var c = ca[i];
//         while (c.charAt(0) == ' ') c = c.substring(1, c.length);
//         if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
//     }
//     return null;
// }
// // Odczytanie wartości UTM z cookies
// var utmParams = getCookie('utm_params');
// // console.log('Odczytane z cookies UTM:', utmParams); // Logowanie do konsoli dla debugowania
// // Wklejenie wartości UTM do pola formularza
// if (utmParams) {
//     var utmFields = document.querySelectorAll('.utm-class input[type="text"]');
//     // console.log(utmFields);
//     utmFields.forEach(function (field) {
//         field.value = utmParams;
//     });
// }
