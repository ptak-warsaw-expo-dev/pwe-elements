(function() {
// Animacja liczb: od 0 do data-count
function animateCount(el) {
    const raw = (el.getAttribute("data-count") || "0").toString().replace(/\s|,/g, "");
    const targetValue = parseFloat(raw) || 0;
    const duration = 3000;
    const start = performance.now();

    function tick(now) {
    const progress = Math.min((now - start) / duration, 1);
    const current = Math.floor(progress * targetValue);
    el.textContent = current.toLocaleString();
    if (progress < 1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
}

// Animacja słupków: wysokość do data-count%
function animateBars() {
    const bars = document.querySelectorAll(".pwe-news-summary-stats__stats-diagram-bar-item");
    const duration = 1200;

    bars.forEach(bar => {
    const percent = parseFloat(bar.getAttribute("data-count")) || 0;
    bar.style.height = "0%";
    const start = performance.now();

    function grow(now) {
        const progress = Math.min((now - start) / duration, 1);
        const value = percent * progress;
        bar.style.height = value + "%";
        if (progress < 1) requestAnimationFrame(grow);
    }
    requestAnimationFrame(grow);
    });
}

// Inicjalizacja z IntersectionObserver (uruchamia się raz przy wejściu w viewport)
document.addEventListener("DOMContentLoaded", function() {
    const countEls = document.querySelectorAll(".countup");
    const section = document.querySelector(".pwe-news-summary-stats");

    if (!section) return;

    const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
        if (!entry.isIntersecting) return;

        // Odpal animacje słupków
        animateBars();

        // Odpal animacje liczników
        countEls.forEach(el => animateCount(el));

        obs.unobserve(entry.target);
    });
    }, { threshold: 0.1 });

    observer.observe(section);
});
})();

jQuery(function($){
    var $slider = $(".pwe-news-summary__gallery-slider");

    if(!$slider.length) return;

    // Nie inicjuj drugi raz
    $slider.not(".slick-initialized").slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        swipeToSlide: true,
        arrows: false,
        dots: false,
        responsive: [
        { breakpoint: 480,  settings: { slidesToShow: 2 } }
        ]
    });
});
