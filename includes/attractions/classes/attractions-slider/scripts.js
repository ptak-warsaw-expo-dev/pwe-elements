window.addEventListener("load", function () {
  const root = document.querySelector(".pwe-attractions-slider");
  if (!root || typeof Swiper === "undefined") return;

  const tabs  = root.querySelectorAll(".pwe-attractions-slider__tab");
  const panes = root.querySelectorAll(".pwe-attractions-slider__pane");
  const bar   = root.querySelector(".pwe-attractions-slider__progress .pwe-attractions-slider__bar");

  let activePane = Array.from(panes).find(p => !p.hidden) || panes[0];
  let unbindProgress = null;

  function bindProgress(sw){
    if (!bar || !sw || !sw.on) return;
    if (unbindProgress) { try { unbindProgress(); } catch(e) {} unbindProgress = null; }
    const handler = (s, time, progress) => { bar.style.width = Math.round((1 - progress) * 100) + "%"; };
    sw.on("autoplayTimeLeft", handler);
    unbindProgress = () => { try { sw.off("autoplayTimeLeft", handler); } catch(e) {} };
    bar.style.width = "0%";
  }

  function makeConfig(swEl){
    const cfg = {
      effect: "coverflow",
      grabCursor: true,
      loop: true,
      loopAdditionalSlides: 5,
      speed: 900,
      autoplay: { delay: 2600, disableOnInteraction: false, pauseOnMouseEnter: true },
      coverflowEffect: { rotate: 20, stretch: 0, scale: 1, depth: 180, modifier: 1, slideShadows: false },
      centeredSlides: true,
      watchSlidesProgress: true,
      slideToClickedSlide: true,
      observer: true,
      observeParents: true,
      observeSlideChildren: true,
      preloadImages: false,
      lazy: true,
      roundLengths: true,
      pagination: {
        el: swEl.querySelector(".swiper-pagination"),
        clickable: true,
        dynamicBullets: true
      },
      breakpoints: {
        0:    { slidesPerView: 1, spaceBetween: 18 },
        480:  { slidesPerView: 2, spaceBetween: 20 },
        1024: { slidesPerView: 3, spaceBetween: 28 },
        1200: { slidesPerView: 4, spaceBetween: 32 },
        1760: { slidesPerView: 5, spaceBetween: 38 }
      }
    };
    return cfg;
  }

  function updateLater(sw){
    try { sw.update(); } catch(e){}
    requestAnimationFrame(() => { try { sw.update(); } catch(e){} });
    requestAnimationFrame(() => requestAnimationFrame(() => { try { sw.update(); } catch(e){} }));
  }

  // ★ funkcja odsłonięcia z lekkim opóźnieniem (gwarantuje animację)
  function reveal(el){
    if (!el) return;
    if (el.classList.contains("pwe-ready")) return;
    requestAnimationFrame(() => requestAnimationFrame(() => {
      el.classList.add("pwe-ready");
    }));
  }

  function initSwiperInPane(pane){
    const el = pane.querySelector(".swiper");
    if (!el) return null;
    if (el.swiper && !el.swiper.destroyed) { // już jest
      updateLater(el.swiper);
      reveal(el); // ★ upewnij się, że jest odsłonięty po powrocie do zakładki
      return el.swiper;
    }

    const sw = new Swiper(el, makeConfig(el));
    updateLater(sw);

    // odsłoń po inicjalizacji i 1–2 klatkach
    reveal(el); // ★

    // po doładowaniu obrazków dociągnij layout (nie wpływa na odsłonięcie)
    el.querySelectorAll("img").forEach(img => {
      if (!img.complete) {
        img.addEventListener("load",  () => updateLater(sw), { once:true });
        img.addEventListener("error", () => updateLater(sw), { once:true });
      }
    });
    return sw;
  }

  function startAutoplay(sw){
    if (!sw || !sw.autoplay) return;
    if (sw.autoplay.start)  sw.autoplay.start();
    if (sw.autoplay.resume) sw.autoplay.resume();
  }
  function stopAutoplay(sw){
    if (sw?.autoplay?.stop) sw.autoplay.stop();
  }

  async function activatePane(nextPane){
    if (!nextPane) return;
    if (nextPane === activePane) return;

    // zatrzymaj poprzedni
    stopAutoplay(activePane.querySelector(".swiper")?.swiper);

    // aria + widoczność
    tabs.forEach(t => {
      const on = t.getAttribute("aria-controls") === nextPane.id;
      t.setAttribute("aria-selected", String(on));
      t.tabIndex = on ? 0 : -1;
    });
    panes.forEach(p => p.hidden = (p !== nextPane));

    // inicjalizuj i odpal
    requestAnimationFrame(() => {
      const sw = initSwiperInPane(nextPane);
      if (sw) {
        startAutoplay(sw);
        bindProgress(sw);
        if (typeof sw.slideToLoop === "function") sw.slideToLoop(sw.realIndex || 0, 0);
        updateLater(sw);
        reveal(nextPane.querySelector(".swiper")); // ★ na wszelki wypadek
      }
      activePane = nextPane;
    });
  }

  // init tylko widocznej instancji (żeby nie migały ukryte)
  panes.forEach(p => { if (!p.hidden) initSwiperInPane(p); });

  // start dla aktywnego
  const initSw = activePane.querySelector(".swiper")?.swiper || initSwiperInPane(activePane);
  if (initSw) { startAutoplay(initSw); bindProgress(initSw); updateLater(initSw); }

  // klik w zakładki
  tabs.forEach(tab => {
    tab.addEventListener("click", () => {
      const pane = document.getElementById(tab.getAttribute("aria-controls"));
      activatePane(pane);
    });
  });
});