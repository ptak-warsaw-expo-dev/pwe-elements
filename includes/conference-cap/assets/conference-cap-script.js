window.speakersData = confCapData.data || {};
window.oneConfMode = confCapData.oneConfMode || false;
jQuery(document).ready(function($){
    $(".conference_cap__lecture-speaker-btn").each(function () {
        $(this).on("click", function () {
            const lectureId = $(this).data("lecture-id") || $(this).closest(".conference_cap__lecture-box").attr("id");
            let data = null;

            const confContainer = $(this).closest(".conference_cap__conf-slug, .konferencja");
            const confSlug = confContainer.attr("id")?.replace(/^conf_/, "");

            const activeDayBtn = confContainer.find(".conference_cap__conf-slug-navigation-day.active-day").first();
            const day = activeDayBtn.length ? activeDayBtn.attr("id")?.split("_").pop() : null;

            const fullKey = confSlug && day ? `${confSlug}_${day}` : null;

            if (lectureId?.startsWith("global_") && confSlug && window.speakersData?.[confSlug]?.[lectureId]) {
                data = window.speakersData[confSlug][lectureId];
            } else if (lectureId && fullKey && window.speakersData?.[fullKey]?.[lectureId]) {
                data = window.speakersData[fullKey][lectureId];
            }

            if (!data) return;

            openSpeakersModal(Array.isArray(data) ? data : [data]);
        });
    });

    function disableScroll() {
        $("body").css("overflow", "hidden");
        $("html").css("overflow", "hidden");
    }

    function enableScroll() {
        $("body").css("overflow", "");
        $("html").css("overflow", "");
    }

    function openSpeakersModal(speakers) {
        var overlay = $("<div>").addClass("custom-modal-overlay");

        var modal = $("<div>").addClass("custom-modal");

        var modalContent = "";
        $(speakers).each(function(index, speaker) {
            modalContent += `<div class="modal-speaker">
                ${ speaker.url ? `<img src="${speaker.url}" alt="${speaker.name}">` : "" }
                <h2>${speaker.name_html}</h2>
                <p>${speaker.bio}</p>
            </div>`;
            if(index < speakers.length - 1) {
                modalContent += "<hr>";
            }
        });

        modal.html(`<button class="custom-modal-close">&times;</button>
            <div class="custom-modal-content">${modalContent}</div>`);
        overlay.append(modal);
        $("body").append(overlay);

        disableScroll();

        setTimeout(function() {
            modal.addClass("visible");
        }, 10);

        $(".custom-modal-close").on("click", function() {
            overlay.remove();
            enableScroll();
        });



        overlay.on("click", function(e) {
            if(e.target === overlay[0]) {
                overlay.remove();
                enableScroll();
            }
        });
        
    }

    function initializeConferenceNavigation() {
        // Uaktualniony selektor obejmuje oba typy kontenerów
        const confTabs = $(".conference_cap__conf-slug, .konferencja");
        const confImages = $(".conference_cap__conf-slug-img");
        const tabs = $(".conference_cap__conf-slug-navigation-day");
        
        // Przełączanie konferencji po kliknięciu obrazka
        confImages.on("click", function () {
            const parentLink = $(this).closest('a');

            if (parentLink.length > 0) {
                return;
            }
        
            const slug = this.id.replace("nav_", "");

            const url = new URL(window.location.href);
            url.searchParams.set("konferencja", slug);
            window.history.replaceState({}, "", url);

            const targetSelector = `#conf_${slug}, #${slug}`;
            const targetContainer = $(targetSelector).first();
            
            if (!targetContainer.length) return;
        
            confTabs.removeClass("active-slug").hide();
            confImages.removeClass("active-slug");
        
            targetContainer.addClass("active-slug").show();
            $(this).addClass("active-slug");
        
            const firstDayButton = targetContainer.find(".conference_cap__conf-slug-navigation-day").first();
            if (firstDayButton.length) {
                firstDayButton.click();
            }
        
            if (parentLink.length === 0) {
                const containerMasthead = document.querySelector('.pwe-menu');
                const offset = containerMasthead ? containerMasthead.offsetHeight : 80;

                const targetPosition = targetContainer.offset().top - offset;

                $("html, body").animate({ scrollTop: targetPosition }, 400);
            }
        
        });
        
        
        // Przełączanie dni w wybranej konferencji
        tabs.on("click", function () {
            const parts = this.id.split("_");
            // Zakładamy, że struktura id przycisku dnia to "tab_slug_dzien"
            const selectedConfSlug = parts[1];
            const selectedDay = parts[2];
            const targetId = `content_${selectedConfSlug}_${selectedDay}`;
                        
            // Znalezienie najbliższego kontenera konferencji, który może mieć klasę .conference_cap__conf-slug lub .konferencja
            const currentConf = $(this).closest(".conference_cap__conf-slug, .konferencja");
            
            // Usunięcie klasy active-day ze wszystkich dni w danej konferencji
            currentConf.find(".conference_cap__conf-slug-navigation-day").removeClass("active-day");
            // Dodanie klasy active-day do klikniętego przycisku
            $(this).addClass("active-day");
            
            // Usunięcie klasy active-content z zawartości dni
            currentConf.find(".conference_cap__conf-slug-content").removeClass("active-content");
            
            // Dodanie klasy active-content do docelowej zawartości dnia
            const targetContent = $(`#${targetId}`);
            if (targetContent.length) {
                targetContent.addClass("active-content");
            }
        });
        
        // Opcjonalnie: ustawienie domyślnego stanu, np. automatyczne kliknięcie pierwszego obrazka
        // if (confImages.length > 0) {
        //     confImages.first().click();
        // }

        const urlParams = new URLSearchParams(window.location.search);
        const confSlug = urlParams.get('konferencja');

        if (confSlug) {
            setTimeout(() => {
                const targetImage = $(`#nav_${confSlug}`);
                if (targetImage.length) {
                    targetImage.trigger("click");
                }
            }, 300); 
        } else if (confCapData.archive && !confCapData.oneConfMode) {
            $(".conference_cap__conf-slug-img").first().trigger("click");
        }

        const allConfs = $(".conference_cap__conf-slug");

        // Jeśli tryb jednej konferencji jest włączony
        if (confCapData.oneConfMode) {
            const allConfs = $(".conference_cap__conf-slug");

            if (allConfs.length > 0) {
                const firstConf = allConfs.first();
                firstConf.addClass("active-slug").show();

                const firstDayBtn = firstConf.find(".conference_cap__conf-slug-navigation-day").first();
                if (firstDayBtn.length) {
                    firstDayBtn.trigger("click");
                } 
            } 
        }

    }

    $("[data-html-inject-id]").each(function () {
        const targetId = $(this).data("html-inject-id");
        const sourceElement = document.getElementById(targetId);
        if (sourceElement) {
            $(this).replaceWith(sourceElement);
        }
    });

    

    initializeConferenceNavigation();

});