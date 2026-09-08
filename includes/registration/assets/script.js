const utm = data_js.source_utm;
const htmlLang = document.documentElement.lang;
const registrationMode = data_js.registration_modes;

function getFullUtmString() {
    const searchParams = window.location.search;
    const utmIndex = searchParams.indexOf('utm_');

    if (utmIndex !== -1) {
        return searchParams.substring(utmIndex);
    }

    return sessionStorage.getItem('user_utm_data') || '';
}

function getUtmSourceValue() {
    const utmString = getFullUtmString();
    if (!utmString) return '';

    const params = new URLSearchParams(utmString);
    return params.get('utm_source') || '';
}

function setHiddenRegistrationFields() {
    const container = document.querySelector(".pwe-registration-container");

    const patronInput = document.querySelector(".patron input");
    if (patronInput && container) {
        patronInput.value = container.getAttribute("fair_group") || "";
    }

    const langInput = document.querySelector(".lang input");
    if (langInput) {
        const pageLang = (document.documentElement.lang || "").trim().toLowerCase();
        langInput.value = pageLang ? pageLang.substring(0, 2) : "";
    }
}

function updateCountryInput() {
    const selectedFlag = document.querySelector(".iti__flag-container .iti__selected-flag, .iti__selected-flag");
    const countryInput = document.querySelector(".country input");

    if (selectedFlag && countryInput) {
        const countryTitle = selectedFlag.getAttribute("title") || selectedFlag.getAttribute("aria-label") || "";
        countryInput.value = countryTitle;
    }
}

function addEventListenersToForm() {
    document.querySelectorAll("input, select, textarea, button").forEach(element => {
        ["change", "input", "click", "focus"].forEach(event => {
            element.addEventListener(event, updateCountryInput);
        });
    });
}

function observeFlagChanges() {
    const selectedFlag = document.querySelector(".iti__selected-flag");
    if (selectedFlag) {
        new MutationObserver(mutations => {
            if (mutations.some(mutation => mutation.attributeName === "aria-expanded" || mutation.attributeName === "title")) {
                updateCountryInput();
            }
        }).observe(selectedFlag, { attributes: true });
    }
}

function getGroupPatron() {
    const patronInput = document.querySelector(".patron input");
    const container = document.querySelector('.pwe-registration-container');

    if (patronInput && container) {
        patronInput.value = container.getAttribute('fair_group') || '';
    }
}

setHiddenRegistrationFields();
addEventListenersToForm();
observeFlagChanges();

window.onload = function () {
    function getCookie(name) {
        let value = "; " + document.cookie;
        let parts = value.split("; " + name + "=");
        if (parts.length === 2) return parts.pop().split(";").shift();
        return null;
    }

    function deleteCookie(name) {
        document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }

    let utmCookie = getCookie("utm_params");
    let utmInput = document.querySelector(".utm-class input");

    if (utmCookie && (utmCookie.includes("utm_source=byli") || utmCookie.includes("utm_source=premium") || utmCookie.includes("utm_source=platyna"))) {
        deleteCookie("utm_params");
    }

    if (utmInput) {
        utmInput.value = getFullUtmString() || utm || '';
    }

    const buttonSubmit = document.querySelector("#pweRegistration .gform_footer input[type=submit]");

    if (buttonSubmit) {
        buttonSubmit.addEventListener("click", function () {
            const emailContainer = document.getElementsByClassName("ginput_container_email")[0];
            const emailValue = emailContainer ? emailContainer.getElementsByTagName("input")[0].value : "";

            let telValue;
            const telContainer = document.getElementsByClassName("ginput_container_phone")[0];

            if (telContainer) {
                telValue = telContainer.getElementsByTagName("input")[0].value;
            } else {
                telValue = "123456789";
            }

            let countryValue = "";
            const countryContainer = document.getElementsByClassName("country")[0];
            if (countryContainer) {
                const countryInput = countryContainer.getElementsByTagName("input")[0];
                if (countryInput) {
                    countryValue = countryInput.value;
                }
            }

            localStorage.setItem("user_email", emailValue);
            localStorage.setItem("user_country", countryValue);
            localStorage.setItem("user_tel", telValue);

            if (htmlLang === "pl-PL") {
                localStorage.setItem("user_direction", "rejpl");
            } else {
                localStorage.setItem("user_direction", "rejen");
            }

            const areaContainer = document.getElementsByClassName("input-area")[0];
            if (areaContainer) {
                const areaInput = areaContainer.getElementsByTagName("input")[0];
                if (areaInput) {
                    localStorage.setItem("user_area", areaInput.value);
                }
            }
        });
    }
};

// Potential exhibitors form & Accreditations
document.addEventListener("DOMContentLoaded", function() {
    const potentialExhibitorsElement = document.querySelector(".pwe-registration.potential-exhibitors");
    const accreditationsElement = document.querySelector(".pwe-registration.accreditations");
    if (potentialExhibitorsElement || accreditationsElement) {
        const customSelect = document.getElementById("fairSelect");
        if (!customSelect) return;

        const optionsContainer = customSelect.querySelector(".pwe-registration-fairs-options-container");
        const searchInput = customSelect.querySelector("#searchInput");
        const selectedText = customSelect.querySelector(".pwe-registration-fairs-selected-text");

        // Show options and search field after clicking customSelect
        customSelect.addEventListener("click", function(event) {
            if (event.target !== searchInput && !event.target.classList.contains("pwe-registration-fairs-option")) {
                customSelect.classList.toggle("open");
                searchInput.value = ""; // Resetuje wartość pola wyszukiwania
                filterOptions(""); // Zresetowanie filtracji
            }
        });

        // Close the select by clicking outside it, but not in the search field
        document.addEventListener("click", function(event) {
            if (!customSelect.contains(event.target) && event.target !== searchInput) {
                customSelect.classList.remove("open");
            }
        });

        // Filter options based on entered text
        searchInput.addEventListener("input", function() {
            const filter = searchInput.value.toLowerCase();
            filterOptions(filter);
        });

        // Function to filter options
        function filterOptions(filter) {
            const options = customSelect.querySelectorAll(".pwe-registration-fairs-option");
            options.forEach(function(option) {
                const nameText = option.getAttribute("name");
                const domainText = option.getAttribute("domain");

                const combinedText = (nameText ? nameText.toLowerCase() : "") + " " + (domainText ? domainText.toLowerCase() : "");

                if (combinedText.indexOf(filter.toLowerCase()) > -1) {
                    option.style.display = "block";
                } else {
                    option.style.display = "none";
                }
            });
        }

        const inputFairName =
            document.querySelector(".accreditations-input-fair-name input") ||
            document.querySelector(".potential-exhibitors-input-fair-name input");

        const inputFairDomain =
            document.querySelector(".accreditations-input-fair-domain input") ||
            document.querySelector(".potential-exhibitors-input-fair-domain input");

        const inputFairDate =
            document.querySelector(".accreditations-input-fair-date input") ||
            document.querySelector(".potential-exhibitors-input-fair-date input");

        const inputFairGroup =
            document.querySelector(".accreditations-input-fair-group input") ||
            document.querySelector(".potential-exhibitors-input-fair-group input");

        const inputFairLang = document.querySelector(".potential-exhibitors-input-fair-lang input");

        const radioInputsLang = document.querySelectorAll(".pwe-registration-fairs-radio-buttons input");
        let lang = "pl-PL";

        if (inputFairLang) {
            inputFairLang.value = "PL";
            lang = (inputFairLang.value === "PL") ? "pl-PL" : "en-US";
        } else {
            lang = htmlLang;
        }

        radioInputsLang.forEach(input => {
            input.addEventListener("change", function() {
                const selectedLanguage = document.querySelector(`input[name="language"]:checked`);
                if (selectedLanguage) {
                    const checkedLabel = selectedLanguage.closest("label");
                    if (inputFairLang && checkedLabel) {
                        inputFairLang.value = checkedLabel.textContent.trim();
                        lang = (inputFairLang.value === "PL") ? "pl-PL" : "en-US";
                        updateDate();
                    }
                }
            });
        });

        function updateDate() {
            const activeOption = document.querySelector(".pwe-registration-fairs-option.active");
            if (!activeOption || !inputFairDate) return;

            const dateStart = activeOption.getAttribute("date-start");
            const dateEnd = activeOption.getAttribute("date-end");

            if (dateStart && dateEnd) {
                const startDate = new Date(dateStart);
                const endDate = new Date(dateEnd);

                function formatDate(date) {
                    const day = date.getDate();
                    const month = date.toLocaleString(lang, { month: "long" });
                    const year = date.getFullYear();
                    return { day, month, year };
                }

                const startDateFormatted = formatDate(startDate);
                const endDateFormatted = formatDate(endDate);

                let fairDate;
                if (startDateFormatted.month === endDateFormatted.month) {
                    fairDate = `${startDateFormatted.day} - ${endDateFormatted.day} ${endDateFormatted.month} ${endDateFormatted.year}`;
                } else {
                    fairDate = `${startDateFormatted.day} ${startDateFormatted.month} - ${endDateFormatted.day} ${endDateFormatted.month} ${endDateFormatted.year}`;
                }

                inputFairDate.value = fairDate;
            } else {
                inputFairDate.value = (lang === "pl-PL") ? "Nowa data wkrótce" : "New date comming soon";
            }
        }

        optionsContainer.addEventListener("click", function(e) {
            if (e.target.classList.contains("pwe-registration-fairs-option")) {
                const options = customSelect.querySelectorAll(".pwe-registration-fairs-option");
                options.forEach(function(option) {
                    option.classList.remove("active");
                });

                if (inputFairName) inputFairName.value = e.target.getAttribute("name");
                if (inputFairDomain) inputFairDomain.value = e.target.getAttribute("domain");
                if (inputFairGroup) inputFairGroup.value = e.target.getAttribute("group") || "";

                e.target.classList.add("active");

                if (e.target.classList.contains('active')) {
                    const domainAttr = e.target.getAttribute('domain');
                    let submit = null;
                    if (potentialExhibitorsElement) {
                        submit = potentialExhibitorsElement.querySelector(".gform_footer .gform_button");
                    } else if (accreditationsElement) {
                        submit = accreditationsElement.querySelector(".gform_footer .gform_button");
                    }

                    if (submit) {
                        if (domainAttr !== null && domainAttr !== '') {
                            submit.classList.add('active');
                        } else submit.classList.remove('active');
                    }
                }

                selectedText.textContent = e.target.textContent;
                customSelect.classList.remove("open");

                updateDate();
            }
        });

        const inputFairId = document.querySelector(".potential-exhibitors-input-id-data input");
        const selectId = document.querySelector(".potential-exhibitors-select-id .gfield_select");
        const form = document.querySelector("#pweRegistration form");

        if (form && selectId && inputFairId) {
            form.addEventListener("submit", function(e) {
                let emailId = selectId.value.split(",");
                if (emailId.length >= 3) {
                    inputFairId.value = emailId[2];
                }
            });
        }

        const confirmationMessage = document.querySelector(".gform_confirmation_message");
        const fairsSelectContainer = document.querySelector(".pwe-registration-fairs-select-container");

        if (confirmationMessage && fairsSelectContainer) {
            fairsSelectContainer.style.display = "none";
        }
    }

    function getLocationPathReg() {
        const urlParams = new URLSearchParams(window.location.search);
        const registrationParam = urlParams.get('reg');

        const utmSource = getUtmSourceValue();

        if (registrationParam) {
            return registrationParam;
        } else if (utmSource === 'byli') {
            return 'vip';
        } else if (utmSource === 'premium') {
            return 'platinum';
        } else if (utmSource === 'platyna') {
            return 'platyna';
        } else {
            let urlPath = window.location.pathname;
            urlPath = urlPath.replace(/^\/en\//, '').replace(/^\/|\/$/g, '');
            return urlPath.length > 0 ? urlPath : "header";
        }
    }

    function setLocationToFormReg() {
        const locationInput = document.querySelector(".location input");
        if (locationInput) {
            const locationPath = getLocationPathReg();
            locationInput.value = locationPath;
        }
    }

    setLocationToFormReg();

    const emailInput = document.querySelector('input[type="email"]') || document.querySelector('.ginput_container_email input');

    if (emailInput) {
        emailInput.addEventListener('change', function() {
            const locationInputContainer = document.querySelector(".location input");
            if (locationInputContainer) {
                setLocationToFormReg();
            }
        });
    }
});