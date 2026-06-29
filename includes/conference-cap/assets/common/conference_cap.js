window.PWEConferenceCap = window.PWEConferenceCap || {};
window.speakersData = (window.confCapData && confCapData.data) || {};
window.oneConfMode = (window.confCapData && confCapData.oneConfMode) || false;

(function ($, namespace) {
  "use strict";

  const state = {
    overlay: null,
  };

  function disableScroll() {
    $("body, html").css("overflow", "hidden");
  }

  function enableScroll() {
    $("body, html").css("overflow", "");
  }

  function closeSpeakersModal() {
    if (state.overlay) {
      state.overlay.remove();
      state.overlay = null;
      enableScroll();
      $(document).off("keydown.pweConferenceCapModal");
    }
  }

  function appendSpeaker($container, speaker, index, total) {
    const $speaker = $("<div>").addClass("modal-speaker");

    if (speaker.url) {
      $("<img>").attr("src", speaker.url).attr("alt", speaker.name || "").appendTo($speaker);
    }

    $("<h2>").html(speaker.name_html || speaker.name || "").appendTo($speaker);
    $("<p>").html(speaker.bio || "").appendTo($speaker);
    $container.append($speaker);

    if (index < total - 1) {
      $container.append($("<hr>"));
    }
  }

  function openSpeakersModal(speakers) {
    closeSpeakersModal();

    const $overlay = $("<div>").addClass("custom-modal-overlay");
    const $modal = $("<div>").addClass("custom-modal");
    const $content = $("<div>").addClass("custom-modal-content");

    $("<button>")
      .addClass("custom-modal-close")
      .attr("type", "button")
      .attr("aria-label", "Close")
      .html("&times;")
      .on("click", closeSpeakersModal)
      .appendTo($modal);

    speakers.forEach(function (speaker, index) {
      appendSpeaker($content, speaker, index, speakers.length);
    });

    $modal.append($content);
    $overlay.append($modal);
    $("body").append($overlay);
    state.overlay = $overlay;

    disableScroll();

    window.setTimeout(function () {
      $modal.addClass("visible");
    }, 10);

    $overlay.on("click", function (event) {
      if (event.target === $overlay[0]) {
        closeSpeakersModal();
      }
    });

    $(document).on("keydown.pweConferenceCapModal", function (event) {
      if (event.key === "Escape") {
        closeSpeakersModal();
      }
    });
  }

  function speakerDataForButton($button) {
    const lectureId = $button.data("lecture-id") || $button.closest(".conference_cap__lecture-box").attr("id");
    const $confContainer = $button.closest(".conference_cap__conf-slug, .konferencja");
    const confSlug = ($confContainer.attr("id") || "").replace(/^conf_/, "");
    const $activeDayBtn = $confContainer.find(".conference_cap__conf-slug-navigation-day.active-day").first();
    const day = $activeDayBtn.length ? ($activeDayBtn.attr("id") || "").split("_").pop() : null;
    const fullKey = confSlug && day ? confSlug + "_" + day : null;

    if (lectureId && lectureId.indexOf("global_") === 0 && confSlug && window.speakersData[confSlug] && window.speakersData[confSlug][lectureId]) {
      return window.speakersData[confSlug][lectureId];
    }

    if (lectureId && fullKey && window.speakersData[fullKey] && window.speakersData[fullKey][lectureId]) {
      return window.speakersData[fullKey][lectureId];
    }

    return null;
  }

  function initializeSpeakersModal() {
    $(document).on("click", ".conference_cap__lecture-speaker-btn", function () {
      const data = speakerDataForButton($(this));

      if (data) {
        openSpeakersModal(Array.isArray(data) ? data : [data]);
      }
    });
  }

  function initializeConferenceNavigation() {
    const $confTabs = $(".conference_cap__conf-slug, .konferencja");
    const $confImages = $(".conference_cap__conf-slug-img");
    const $tabs = $(".conference_cap__conf-slug-navigation-day");

    $confImages.on("click", function () {
      const $parentLink = $(this).closest("a");

      if ($parentLink.length > 0) {
        return;
      }

      const slug = this.id.replace("nav_", "");
      const url = new URL(window.location.href);
      url.searchParams.set("konferencja", slug);
      window.history.replaceState({}, "", url);

      const $targetContainer = $("#conf_" + slug + ", #" + slug).first();
      if (!$targetContainer.length) {
        return;
      }

      $confTabs.removeClass("active-slug").hide();
      $confImages.removeClass("active-slug");
      $targetContainer.addClass("active-slug").show();
      $(this).addClass("active-slug");

      const $firstDayButton = $targetContainer.find(".conference_cap__conf-slug-navigation-day").first();
      if ($firstDayButton.length) {
        $firstDayButton.trigger("click");
      }

      const masthead = document.querySelector(".pwe-menu");
      const offset = masthead ? masthead.offsetHeight : 80;
      $("html, body").animate({ scrollTop: $targetContainer.offset().top - offset }, 400);
    });

    $tabs.on("click", function () {
      const parts = this.id.split("_");
      const selectedConfSlug = parts[1];
      const selectedDay = parts[2];
      const $currentConf = $(this).closest(".conference_cap__conf-slug, .konferencja");

      $currentConf.find(".conference_cap__conf-slug-navigation-day").removeClass("active-day");
      $(this).addClass("active-day");
      $currentConf.find(".conference_cap__conf-slug-content").removeClass("active-content");
      $("#content_" + selectedConfSlug + "_" + selectedDay).addClass("active-content");
    });

    const urlParams = new URLSearchParams(window.location.search);
    const confSlug = urlParams.get("konferencja");

    if (confSlug) {
      window.setTimeout(function () {
        $("#nav_" + confSlug).trigger("click");
      }, 300);
    } else if (window.confCapData && confCapData.archive && !confCapData.oneConfMode) {
      $(".conference_cap__conf-slug-img").first().trigger("click");
    }

    if (window.confCapData && confCapData.oneConfMode) {
      const $firstConf = $(".conference_cap__conf-slug").first();
      $firstConf.addClass("active-slug").show();
      $firstConf.find(".conference_cap__conf-slug-navigation-day").first().trigger("click");
    }
  }

  function initializeHtmlInjection() {
    $("[data-html-inject-id]").each(function () {
      const targetId = $(this).data("html-inject-id");
      const sourceElement = document.getElementById(targetId);

      if (sourceElement) {
        $(this).replaceWith(sourceElement);
      }
    });
  }

  namespace.openSpeakersModal = openSpeakersModal;
  namespace.closeSpeakersModal = closeSpeakersModal;

  $(function () {
    initializeSpeakersModal();
    initializeHtmlInjection();
    initializeConferenceNavigation();
  });
})(jQuery, window.PWEConferenceCap);

