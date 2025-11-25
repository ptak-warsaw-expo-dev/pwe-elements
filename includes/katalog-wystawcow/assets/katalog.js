jQuery(document).ready(function ($) {
    if (typeof katalog_data !== 'undefined' && !('format' in katalog_data)) {
        //Ustuwa tytuł uncoda jeżeli jest wpisany tytuł we wtyczce
        $(".catalog-custom-title").parent().siblings(".vc_custom_heading_wrap").hide();

        var exhibitorsAll = Object.entries(katalog_data);

        var exhibitors = exhibitorsAll.reduce((acc, curr) => {
            const name = curr[1].Nazwa_wystawcy;
            const existingEntryIndex = acc.findIndex(item => item[1].Nazwa_wystawcy === name);

            if (existingEntryIndex !== -1) {
                const existingDate = acc[existingEntryIndex][1].Data_sprzedazy;
                const currentDate = curr[1].Data_sprzedazy;

                // Porównaj daty i zachowaj wpis z nowszą datą
                if (new Date(currentDate) > new Date(existingDate)) {
                    acc[existingEntryIndex] = curr;
                }
            } else {
                // Brak istniejącego wpisu o tej nazwie, dodaj do akumulatora
                acc.push(curr);
            }
            return acc;
        }, []);

        if ($(".exhibitors-catalog").find("#full").length > 0) {
            /* SEARCH ELEMENT */
            const $allExhibitorsArray = $(".exhibitors__container-list");

            $("#search").on("input", function () {
                const input = $(this).val().toLocaleLowerCase();
                $allExhibitorsArray.each(function () {
                    if ($(this).find("h2").text().toLocaleLowerCase().includes(input) || $(this).find("p").text().toLocaleLowerCase().includes(input)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            const localLangKat = $("html").attr("lang");

            /* MODAL ELEMENT */
            const $modal = $("<div></div>")
                .addClass("modal")
                .attr("id", "my-modal");

            $allExhibitorsArray.each(function () {
                $(this).on("click", function () {
                    var targetExhibitor = [];
                    const exhibitorName = $(this).find(".exhibitors__container-list-text-name").text();
                    exhibitors.forEach(function (item) {
                        if (item[1]["Nazwa_wystawcy"] == exhibitorName) {
                            targetExhibitor = item[1];
                        }
                    });

                    let exhibitorsUrl = targetExhibitor["www"];

                    if (exhibitorsUrl && !exhibitorsUrl.startsWith('https://www.')) {
                        exhibitorsUrl = "https://" + exhibitorsUrl.replace(/^(https?|ftp):\/\/(www\.)?|(www\.)?/, "");
                    }

                    var modalBox = `<div class="modal__elements">
                                        <div class="modal__elements-block">
                                            ${targetExhibitor["URL_logo_wystawcy"] ? `<div class="modal__elements-img" style="background-image: url(${targetExhibitor["URL_logo_wystawcy"]});"></div>` : ""}
                                            <div class="modal__elements-text">
                                                <h3>${targetExhibitor["Nazwa_wystawcy"]}</h3>`;

                    if (localLangKat == "pl-PL") {
                        modalBox += (targetExhibitor["Telefon"]) ? `<p>Numer telefonu: <b><a href="tel:${targetExhibitor['Telefon']}">${targetExhibitor['Telefon']}</a></b></p>` : "";

                        modalBox += (targetExhibitor["Email"]) ? `<p>Adres Email: <b><a href="mailto:${targetExhibitor['Email']}">${targetExhibitor['Email']}</a></b></p>` : "";

                        modalBox += (exhibitorsUrl) ? `<p>Strona www: <b><a target="_blank" href="${exhibitorsUrl}">${exhibitorsUrl}</a></b></p>` : "";

                        modalBox += (targetExhibitor["Numer_stoiska"]) ? `<p>Stoisko: ${targetExhibitor['Numer_stoiska']}</p>` : "";

                        modalBox += (targetExhibitor["Opis_pl"]) ? `<p>${targetExhibitor['Opis_pl']}</p>` : "";

                        modalBox += `       </div>
                                        </div>
                                        <div class="modal_elements-button">
                                            <button class="close">Zamknij</button>`;
                    } else {
                        modalBox += (targetExhibitor["Telefon"]) ? `<p>Phone number: <b><a href="tel:${targetExhibitor['Telefon']}">${targetExhibitor['Telefon']}</a></b></p>` : "";

                        modalBox += (targetExhibitor["Email"]) ? `<p>E-mail adress: <b><a href="mailto:${targetExhibitor['Email']}">${targetExhibitor['Email']}</a></b></p>` : "";

                        modalBox += (exhibitorsUrl) ? `<p>Web page: <b><a target="_blank" href="${exhibitorsUrl}">${exhibitorsUrl}</a></b></p>` : "";

                        modalBox += (targetExhibitor["Numer_stoiska"]) ? `<p>Stand: ${targetExhibitor['Numer_stoiska']}</p>` : "";

                        modalBox += (targetExhibitor["Opis_en"]) ? `<p>${targetExhibitor['Opis_en']}</p>` : "";

                        modalBox += `       </div>
                                        </div>
                                        <div class="modal_elements-button">
                                            <button class="close">Close</button>`;
                    }
                    modalBox += `       </div>
                                    </div>`;

                    $modal.html(modalBox);

                    $(".exhibitors-catalog").find("#full").append($modal);

                    $modal.css("display", "flex");
                    const $closeBtn = $modal.find(".close");

                    $closeBtn.on("click", function () {
                        $modal.hide();
                    });

                    $modal.on("click", function (event) {
                        if ($(event.target)[0] === $modal[0]) {
                            $modal.hide();
                        }
                    });
                });
            });
        }
    }
});