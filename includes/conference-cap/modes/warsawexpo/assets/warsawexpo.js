/* --------------------------------------------------------------
                * --------------------------------------------------------------*/
                jQuery(function ($) {

                /* ============================================================
                * ============================================================*/
                $(".conference-cap-warsawexpo__schedule-container").each(function () {
                const $scroller  = $(this);
                let down = false, startX = 0, startY = 0, startScroll = 0;

                $scroller
                    .on("touchstart mousedown", function (e) {
                        const p   = e.originalEvent.touches ? e.originalEvent.touches[0] : e;
                        if (e.type === "mousedown" && e.which !== 1) return;      // Left mouse button only
                        down        = true;
                        startX      = p.pageX;
                        startY      = p.pageY;
                        startScroll = this.scrollLeft;
                        $scroller.addClass("grabbing");
                    })

                    .on("touchmove mousemove", function (e) {
                        if (!down) return;

                        const p  = e.originalEvent.touches ? e.originalEvent.touches[0] : e;
                        const dx = p.pageX - startX;
                        const dy = p.pageY - startY;

                        /* Capture the gesture only when horizontal movement dominates */
                        if (Math.abs(dx) > Math.abs(dy)) {
                            if (e.originalEvent.cancelable) e.preventDefault();
                            this.scrollLeft = startScroll - dx;
                        }
                    })

                    .on("touchend touchcancel mouseup mouseleave", () => {
                        down = false;
                        $scroller.removeClass("grabbing");
                    });
                });

                /* ============================================================
                * ============================================================*/
                const $dayTabs    = $(".conference-cap-warsawexpo__day-tabs button");
                const $dayContent = $(".conference-cap-warsawexpo__day-content");

                $dayTabs.on("click", function () {
                    const day = $(this).data("day");
                    $dayTabs.removeClass("active");
                    $(this).addClass("active");
                    $dayContent.removeClass("active").filter("."+day).addClass("active");
                    rebuildFairBar(day);
                });

                /* ============================================================
                * ============================================================*/
                const $fairBar = $(".conference-cap-warsawexpo__expo-tabs");

                function rebuildFairBar(dayKey) {
                    const $day      = $(".conference-cap-warsawexpo__day-content."+dayKey);
                    if (!$day.length) return;

                    $fairBar.empty();
                    const fairs = {};

                    // Collect unique fairs from fair-XXX classes
                    $day.find(".conference-cap-warsawexpo__hall-column").each(function () {
                        const m = this.className.match(/fair-([^\s]+)/);
                        if (m) fairs[m[1]] = true;
                    });

                    // Generate tiles
                    $.each(fairs, function (fair, _v) {
                        var $btn = $("<button class=\"conference-cap-warsawexpo__fair-btn\"></button>")
                                    .data("fair", fair)
                                    .append(
                                        "<img src=\"https://" + fair + "/doc/kafelek.jpg\" alt=\"" + fair + "\">"
                                    );
                        $fairBar.append($btn);
                    });

                    bindFairClicks(dayKey);

                }

                function bindFairClicks(dayKey) {
                    $fairBar.find(".conference-cap-warsawexpo__fair-btn").off("click").on("click", function () {
                        const $btn      = $(this);
                        const isActive  = $btn.hasClass("active");

                        $fairBar.find(".conference-cap-warsawexpo__fair-btn").removeClass("active");

                        if (isActive) {
                            showAllColumns(dayKey);
                            return;
                        }

                        $btn.addClass("active");
                        filterColumns($btn.data("fair"), dayKey);
                    });
                }

                // Show only columns from the selected fair
                function filterColumns(slug, dayKey) {
                    $(".conference-cap-warsawexpo__day-content."+dayKey+" .conference-cap-warsawexpo__hall-column").each(function () {
                        const fair = (this.className.match(/fair-([^\s]+)/) || [null,""])[1];
                        $(this).toggle(fair === slug);
                    });
                }

                // Show all columns
                function showAllColumns(dayKey) {
                    $(".conference-cap-warsawexpo__day-content."+dayKey+" .conference-cap-warsawexpo__hall-column").show();
                }

                /* Initial run for day1 */
                rebuildFairBar("day1");
                });
