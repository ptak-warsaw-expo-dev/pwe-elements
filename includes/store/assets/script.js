const apiKey = store_js.api_key;
const currentGroup = store_js.current_group;

// Scroll to top of page
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
}

function showFeaturedService(featuredId) {
    // Hide all pwe-store__featured-service sections
    const featuredServices = document.querySelectorAll('.pwe-store__featured-service');
    featuredServices.forEach(service => service.classList.remove("active"));

    // Show the appropriate section
    const featuredService = document.getElementById(featuredId);
    if (featuredService) {
        featuredService.classList.add("active");
    }
}

// Hide the main section and show the category header
function showCategoryHeader(mainSection, categoryHeaders, categoryHeadersActive) {
    // Hide main section
    mainSection.style.display = "none";
    categoryHeaders.forEach(header => {
        if (header) {
            // Show the category header
            header.style.display = "none";
        }
    });
    categoryHeadersActive.forEach(headerActive => {
        if (headerActive) {
            // Show the category header
            headerActive.style.display = "flex";
        }
    });
}

// Show the main section and hide the category header
function hideCategoryHeader(mainSection, categoryHeaders) {
    // Show main section
    mainSection.style.display = "block";
    categoryHeaders.forEach(header => {
        if (header) {
            // Hide the category header
            header.style.display = "none";
        }
    });
}

// Function to remove query parameters from URL
function removeURLParams() {
    // Get the current URL
    const url = new URL(window.location.href);
    const category = url.searchParams.get('category');

    // Clear all parameters
    url.search = '';

    // If the category existed, add it back
    if (category) {
        url.searchParams.set('category', category);
    }

    // Update url in history without reload
    window.history.replaceState({}, '', url.toString());
}

document.addEventListener('DOMContentLoaded', function() {
    const pweStore = document.querySelector(".pwe-store");
    const elImages = document.querySelectorAll(".pwe-store__featured-image");
    const pweMenu = document.querySelector("#pweMenu");
    const mainSection = document.querySelector('.pwe-store__main-section');

    if (pweStore) {

        function updateImagesPosition() {
            const viewportHeight = window.innerHeight;

            elImages.forEach(elImage => {
                const elContainer = elImage.parentElement;
                const containerRect = elContainer.getBoundingClientRect();

                if (pweMenu) {
                    if (containerRect.top >= pweMenu.offsetHeight) {
                        elImage.classList.remove("sticky");
                        elImage.style.top = "0px";
                    } else if (containerRect.top < pweMenu.offsetHeight && containerRect.bottom > viewportHeight) {
                        elImage.classList.add("sticky");
                        elImage.style.top = pweMenu.offsetHeight + 50 + "px";
                    } 
                } else {
                    if (containerRect.top >= 0) {
                        elImage.classList.remove("sticky");
                    } else if (containerRect.top < 0 && containerRect.bottom > viewportHeight) {
                        elImage.classList.add("sticky");
                    } 
                }
            });
        }

        window.addEventListener("resize", function () {
            if (window.innerWidth > 1024) {
                window.addEventListener("scroll", updateImagesPosition);
                updateImagesPosition();
            }
        }); 

        if (window.innerWidth > 1024) {
            window.addEventListener("scroll", updateImagesPosition);
            updateImagesPosition();
        }

        // Handle clicking the "MORE" button and card item
        const moreButtons = document.querySelectorAll('.pwe-store__service-card a:not(.pwe-store__buy-ticket-button)');
        moreButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const featuredId = this.getAttribute('data-featured');
                showFeaturedService(featuredId);

                // Update the URL by adding a parameter
                const url = new URL(window.location);
                url.searchParams.set('featured-service', featuredId);
                window.history.pushState({}, '', url);

                const categoryActive = document.querySelector('.pwe-store__section-hide:has(.pwe-store__featured-service.active)');
                if (categoryActive) {
                    categoryActive.style.display = "block";
                }
                
                const categoryHeaders = document.querySelectorAll('.pwe-store__section-hide:has(.pwe-store__featured-service) .pwe-store__category-header');
                const categoryHeadersActive = document.querySelectorAll('.pwe-store__section-hide:has(.pwe-store__featured-service.active) .pwe-store__category-header');
                // Hide the main section and show the category header
                showCategoryHeader(mainSection, categoryHeaders, categoryHeadersActive);

                // Scroll to top of page
                scrollToTop();
            });
        });

        // Handling parameter in URL
        const urlParams = new URLSearchParams(window.location.search);
        const featuredServiceParam = urlParams.get('featured-service');
        if (featuredServiceParam) {
            showFeaturedService(featuredServiceParam);

            const categoryHeaders = document.querySelectorAll('.pwe-store__section-hide:has(.pwe-store__featured-service) .pwe-store__category-header');
            const categoryHeadersActive = document.querySelectorAll(`.pwe-store__section-hide:has(.pwe-store__featured-service.active) .pwe-store__category-header`);
            // Hide the main section and show the category header
            showCategoryHeader(mainSection, categoryHeaders, categoryHeadersActive);

            // Scroll to top of page
            scrollToTop();
        }

        const sortingButtons = document.querySelectorAll(".pwe-store__category-item"); 
        const currentUrl = new URL(window.location.href);
        const categoryParam = currentUrl.searchParams.get("category");
        const mainSectionsText = document.querySelectorAll('.pwe-store__main-section-text');

        // Clicking on the category buttons
        sortingButtons.forEach(button => {
            if (!button.classList.contains("dropdown")) {
                button.addEventListener("click", function() {
                    // Get ID of button
                    const buttonId = this.id;

                    // Update category parameter
                    currentUrl.search = '';
                    currentUrl.searchParams.set('category', buttonId);
                    window.history.pushState({}, '', currentUrl.toString());

                    // Removing the 'active' class from all buttons
                    sortingButtons.forEach(b => b.classList.remove('active'));

                    // Adding 'active' class to clicked button
                    this.classList.add('active');

                    const cardsContainers = document.querySelectorAll('.pwe-store__section');

                    // Removing the 'active' class from all elements
                    cardsContainers.forEach(e => e.classList.remove('active'));

                    // Iterate through all elements and check their IDs
                    cardsContainers.forEach(element => {
                        const category = element.getAttribute('category');
                
                        // If the 'category' attribute is the same as the button ID, we add the 'active' class
                        if (category === buttonId) {
                            element.classList.add('active');
                        }
                    });

                    mainSectionsText.forEach(item => {
                        // Split the className by spaces and check if the exact class is present
                        const classes = item.className.split(' ');
                        
                        // Only display the item if the buttonId matches exactly one of the classes
                        if (classes.indexOf(buttonId) !== -1 && classes.length > 1) {
                            item.style.display = "block";
                        } else {
                            item.style.display = "none";
                        }
                    });

                    const hideElements = document.querySelectorAll('.pwe-store__section-hide');
                    // Remove the 'active' class from all elements
                    hideElements.forEach(e => e.style.display = "none");
                    // Iterate over all elements and check their IDs
                    hideElements.forEach(element => {
                        // If the element ID contains the button ID, add the 'active' class
                        if (element.id.includes(buttonId)) {
                            element.style.display = "block";
                        }
                    });

                    const featuredService = document.querySelectorAll(".pwe-store__featured-service");
                    featuredService.forEach(service => {
                        service.classList.remove('active');
                    });

                    const categoryHeaders = document.querySelectorAll('.pwe-store__section-hide:has(.pwe-store__featured-service) .pwe-store__category-header');
                    // Show the main section and hide the category header
                    hideCategoryHeader(mainSection, categoryHeaders);

                    const categoriesSection = document.querySelector(".pwe-store__anchor");
                    const elementTop = categoriesSection.getBoundingClientRect().top + window.scrollY;

                    window.scrollTo({
                        top: elementTop - 72,
                        behavior: "smooth"
                    });
                    
                });
            }
        });

        // If the button is not active and the URL does not contain category
        if (!categoryParam) {
            const activeButton = Array.from(sortingButtons).find(btn => btn.classList.contains("active"));
        
            if (!activeButton) {
                const firstButton = sortingButtons[0];
                firstButton.classList.add("active");
        
                const firstButtonId = firstButton.id;
                const newUrl = new URL(window.location.href);
                newUrl.searchParams.set("category", firstButtonId);
                window.history.replaceState({}, '', newUrl.toString());
        
                // Trigger a click to load the appropriate category
                firstButton.click();
            } else {
                const newUrl = new URL(window.location.href);
                newUrl.searchParams.set("category", activeButton.id);
                window.history.replaceState({}, '', newUrl.toString());
            }
        }
        
        // If the URL has a category, set the active button and show the correct category
        if (categoryParam) {
            sortingButtons.forEach(btn => {
                btn.classList.remove("active");
                if (btn.id === categoryParam) {
                    btn.classList.add("active");
                    const currentCardsContainer = document.querySelector(`.pwe-store__section[category="${categoryParam}"]`)
                    if (currentCardsContainer) {
                        currentCardsContainer.classList.add("active");
                    }

                    mainSectionsText.forEach(item => {
                        // Split the className by spaces and check if the exact class is present
                        const classes = item.className.split(' ');
                        
                        // Only display the item if the buttonId matches exactly one of the classes
                        if (classes.indexOf(btn.id) !== -1 && classes.length > 1) {
                            item.style.display = "block";
                        } else {
                            item.style.display = "none";
                        }
                    });
                }
            });
        }

        // Back to the shop
        const arrowBack = document.querySelectorAll(".pwe-store__category-header-arrow");
        arrowBack.forEach(button => {
            button.addEventListener("click", function (event) {
                event.preventDefault();
        
                mainSection.style.display = "block";

                const categoryHeaders = document.querySelectorAll('.pwe-store__section-hide:has(.pwe-store__featured-service) .pwe-store__category-header');
                // Show the main section and hide the category header
                hideCategoryHeader(mainSection, categoryHeaders);
        
                const featuredService = document.querySelectorAll(".pwe-store__featured-service");
                featuredService.forEach(service => {
                    service.classList.remove('active');
                });
        
                removeURLParams();
        
                // Scroll to top of page
                scrollToTop();
            });
        });

        // Gallery popup
        let enableScrolling = true;
        window.isDragging = false;
        document.querySelectorAll(".pwe-store__featured-gallery img").forEach((image, index) => {
            image.addEventListener("click", (e) => {

                if (window.isDraggingMedia) {
                    e.preventDefault(); // Block the opening of the modal if there was movement
                    window.isDraggingMedia = false; // Reset the flag after the click is handled
                    return;
                }

                // Find the closest .pwe-store__featured-gallery container
                const galleryContainer = image.closest('.pwe-store__featured-gallery');
                
                // Get all images inside this gallery container
                const imagesArray = Array.from(galleryContainer.querySelectorAll('img'));

                // Create popup
                const popupDiv = document.createElement("div");
                popupDiv.className = "pwe-media-gallery-popup";

                // Left arrow for previous image
                const leftArrow = document.createElement("span");
                leftArrow.innerHTML = "&#10094;"; // HTML entity for left arrow
                leftArrow.className = "pwe-media-gallery-left-arrow pwe-media-gallery-arrow";
                popupDiv.appendChild(leftArrow);

                // Right arrow for next image
                const rightArrow = document.createElement("span");
                rightArrow.innerHTML = "&#10095;"; // HTML entity for right arrow
                rightArrow.className = "pwe-media-gallery-right-arrow pwe-media-gallery-arrow";
                popupDiv.appendChild(rightArrow);
        
                // Close btn
                const closeSpan = document.createElement("span");
                closeSpan.innerHTML = "&times;";
                closeSpan.className = "pwe-media-gallery-close";
                popupDiv.appendChild(closeSpan);
        
                const popupImage = document.createElement("img");
                popupImage.src = image.getAttribute("src");
                popupImage.alt = "Popup Image";
                popupDiv.appendChild(popupImage);
        
                // Add popup to <body>
                document.body.appendChild(popupDiv);
                popupDiv.style.display = "flex";

                disableScroll();
                enableScrolling = false;

                // Function to change image in popup
                let currentIndex = imagesArray.indexOf(image);

                const changeImage = (direction) => {
                    // Applying the fade-out class before changing the image source
                    popupImage.classList.add("fade-out");
                    popupImage.classList.remove("fade-in");

                    setTimeout(() => {
                        currentIndex += direction;

                        if (currentIndex >= imagesArray.length) {
                            currentIndex = 0; // Goes back to the first image
                        } else if (currentIndex < 0) {
                            currentIndex = imagesArray.length - 1; // Goes to the last image
                        }

                        popupImage.src = imagesArray[currentIndex].getAttribute("src");

                        // Remove fade-out class and add fade-in after image source change
                        popupImage.classList.remove("fade-out");
                        popupImage.classList.add("fade-in");
                    }, 100);
                };

                leftArrow.addEventListener("click", () => changeImage(-1));
                rightArrow.addEventListener("click", () => changeImage(1));

                // Remove popup when clicking the close button
                closeSpan.addEventListener("click", () => {
                    popupDiv.remove();
                    enableScroll();
                    enableScrolling = true;
                });

                // Remove popup when clicking outside the image
                popupDiv.addEventListener("click", (event) => {
                    if (event.target === popupDiv) { // Checks if the clicked element is the popupDiv itself
                        popupDiv.remove();
                        enableScroll();
                        enableScrolling = true;
                    }
                });
            });
        });

        // Prevent scrolling on touchmove when enableScrolling is false
        document.body.addEventListener("touchmove", (event) => {
            if (!enableScrolling) {
                event.preventDefault();
            }
        }, { passive: false });

        // Disable page scrolling
        function disableScroll() {
            document.body.style.overflow = "hidden";
            document.documentElement.style.overflow = "hidden";
        }

        // Enable page scrolling
        function enableScroll() {
            document.body.style.overflow = "";
            document.documentElement.style.overflow = "";
        }


        // Clicking on the language change link
        const wpmlLinks = document.querySelectorAll('a:has(img.wpml-ls-flag)');
        wpmlLinks.forEach(link => {
            if (link) {
                link.addEventListener('click', function(event) {
                    // Getting the original link URL
                    let originalUrl = link.href;

                    // Getting all parameters from the current page URL
                    const currentUrlParams = new URLSearchParams(window.location.search);

                    // Adding parameter to original link
                    const url = new URL(originalUrl);
                    currentUrlParams.forEach((value, key) => {
                        url.searchParams.set(key, value);
                    });

                    // Redirect to a new link with added parameters
                    window.location.href = url.toString();

                    event.preventDefault();
                });
            }
        });

        // Create modal
        const packageCards = document.querySelectorAll(".pwe-store__packages-section-hide .pwe-store__service-card");
        packageCards?.forEach(card => {
            card.addEventListener("click", function (event) {
                event.preventDefault();

                const cardAttribute = card.getAttribute("data-featured");
                const selectItem = document.querySelector(`#${cardAttribute}`);

                if (selectItem) {
                    // Placing the selected element in the modal
                    const modalContent = document.querySelector(".pwe-store__modal-content-placeholder");
                    modalContent.innerHTML = "";
                    const clonedItem = selectItem.cloneNode(true);
                    modalContent.appendChild(clonedItem);

                    const modal = document.querySelector("#pweStoreModal");
                    modal.style.display = "flex";

                    clonedItem.id = cardAttribute + "-modal";
                    clonedItem.classList.add("pwe-store__featured-service-modal");
                    clonedItem.style.display = "flex";
                    clonedItem.style.position = "relative";

                    document.querySelector("body").style.setProperty('overflow', 'hidden', 'important');
                }
            });
        });

        const modalContent = document.querySelector(".pwe-store__modal-content");
        const closeBtn = document.querySelector(".pwe-store__modal-close-btn");

        modalContent.addEventListener("scroll", function() {
            const scrollTop = modalContent.scrollTop;
            const offset = 0;

            closeBtn.style.top = `${scrollTop + offset}px`;
        });

        // Closing modal
        const modal = document.getElementById("pweStoreModal");

        closeBtn.addEventListener("click", function () {
            modal.style.display = "none";
            document.querySelector("body").style.removeProperty('overflow');
        });

        // Close the modal by clicking outside it
        window.addEventListener("click", function (event) {
            if (event.target === modal) {
                modal.style.display = "none";
                document.querySelector("body").style.removeProperty('overflow');
            }
        });
    }
});

// Handling a click on the reservation button
jQuery(document).ready(function ($) {
    const pweStore = $(".pwe-store");
    const mainSection = $('.pwe-store__main-section');
    const htmlLang = document.documentElement.lang.split('-')[0];
    const contactFormModal = $("#pweStoreFormModal");
    let currentDomain = window.location.hostname;
    let sluger = "";
    let domain = "";

    $(".pwe-store__form-modal-open").each(function () {
        $(this).on("click", function () {
    
            if (currentDomain === "warsawexpo.eu") {
    
                if (pweStore) {
                    // Hide all children pweStore
                    $(pweStore).children().css("display", "none");
                }
    
                const fairsContainer = $(".pwe-store__fairs");
                fairsContainer.css("display", "flex");
    
                setTimeout(() => {
                    fairsContainer.css("opacity", "1");
                }, 500);
    
                // Back to the shop
                $(".pwe-store__fairs-arrow-back").off("click").on("click", function () {
                    $(pweStore).children().css("display", "block");
    
                    const categoryHeaders = $('.pwe-store__section-hide:has(.pwe-store__featured-service) .pwe-store__category-header');
                    // Show main section
                    mainSection.css("display", "block");
                    categoryHeaders.forEach(header => {
                        if (header) {
                            // Hide the category header
                            header.css("display", "none");
                        }
                    });
    
                    $(".pwe-store__featured-service").removeClass("active");
    
                    fairsContainer.css("opacity", "0");
                    fairsContainer.css("display", "none");
    
                    removeURLParams();
                });
    
                // Search engine
                const searchInput = $(".pwe-store__fairs-search-input");
                searchInput.off("input").on("input", function () {
                    const searchTerm = $(this).val().toLowerCase();
    
                    $(".pwe-store__fairs-item").each(function () {
                        const $item = $(this);
                        const name = $item.data("name").toLowerCase();
                        const tooltip = $item.data("tooltip").toLowerCase();
    
                        if (name.includes(searchTerm) || tooltip.includes(searchTerm)) {
                            $item.css("display", "block");
                        } else {
                            $item.css("display", "none");
                        }
                    });
                });
    
                // Handling clicking on an item
                $(".pwe-store__fairs-item").off("click").on("click", function () {
                    domain = $(this).data("domain");

                    contactFormModal.show();
                });

                sluger = $(this).closest(".pwe-store__service").data("slug");
    
                scrollToTop();
                removeURLParams();
            } else {
                contactFormModal.show();
                domain = window.location.hostname;
                sluger = $(this).closest(".pwe-store__service").data("slug");
    
                removeURLParams();
            }
        });
    });
    
    contactFormModal.find(".pwe-store__form-modal-close").on("click", function () {
        contactFormModal.hide();
    });

    $(window).on("click", function (e) {
        if ($(e.target).is(contactFormModal)) {
            contactFormModal.hide();
        }
    });

    $(".show-consent").on("click", function () {
        $(this).closest(".pwe-store__form-modal-form").find(".pwe-store__form-modal-consent-desc").toggle(400);
    });

    $(".pwe-store__form-modal-submit").click(function () {
        const $submitBtn = $(this);
    
        // Get data from form
        const email = $(".pwe-store__form-modal-input_email").val().trim();
        const phone = $(".pwe-store__form-modal-input_tel").val().trim();
        const consent = $(".pwe-store__form-modal-consent-checkbox").is(":checked");
    
        // Validation – are all fields filled in?
        if (!email || !phone || !consent) {
            alert(htmlLang === "pl"
                ? "Proszę wypełnić wszystkie pola i zaznaczyć zgodę."
                : "Please fill in all fields and accept the consent.");
            return;
        }
    
        // Block the button while submitting the form
        $submitBtn.prop("disabled", true).text(
            htmlLang === "pl" ? "Wysyłanie..." : "Sending..."
        );
    
        const productName = $('.pwe-store__service[data-slug="' + sluger + '"]')
            .first()
            .find('.pwe-store__service-name-mailing')
            .text()
            .trim();
    
        $.post("https://warsawexpo.eu/wp-content/themes/uncode-child/store/store-contact.php", {
            password: apiKey,
            email: email,
            phone: phone,
            consent: consent,
            domain: domain,
            product: productName,
            slug: sluger,
            lang: htmlLang,
            group: currentGroup
        }, function (response) {
            // console.log(response);
            if (response) {
                $submitBtn.remove();
                $(".pwe-store__form-modal-form").html(
                    htmlLang === "pl"
                        ? "<p>Dziękujemy za wypełnienie formularza kontaktowego. Skontaktujemy się z Państwem wkrótce.</p>"
                        : "<p>Thank you for completing the contact form. We will get in touch with you shortly.</p>"
                ).append(
                    $("<div>").addClass("pwe-store__form-modal-progress-bar").css({
                        width: "100%",
                        height: "5px",
                        backgroundColor: "#4caf50",
                        marginTop: "10px",
                        transition: "width 10s linear"
                    })
                ).append(
                    $("<button>").addClass("pwe-store__form-modal-close-btn")
                        .text(htmlLang === "pl" ? "Zamknij" : "Close")
                        .on("click", function () {
                            contactFormModal.hide();
                        })
                );
    
                setTimeout(function () {
                    $(".pwe-store__form-modal-progress-bar").css("width", "0%");
                }, 100);
    
                setTimeout(function () {
                    contactFormModal.hide();
                }, 10000);
            } else {
                // If an error occurred - unlock the button
                $submitBtn.prop("disabled", false).text(
                    htmlLang === "pl" ? "Wyślij" : "Submit"
                );
                alert(htmlLang === "pl"
                    ? "Wystąpił błąd. Spróbuj ponownie."
                    : "An error occurred. Please try again.");
            }
        }).fail(function () {
            $submitBtn.prop("disabled", false).text(
                htmlLang === "pl" ? "Wyślij" : "Submit"
            );
            alert(htmlLang === "pl"
                ? "Błąd połączenia z serwerem."
                : "Connection error.");
        });
    });
    
});



