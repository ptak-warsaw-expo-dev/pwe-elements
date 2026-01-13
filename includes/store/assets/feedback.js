(function () {
    const widget = document.querySelector('#pweStore .store-feedback');
    if (!widget) return;

    const AUTO_SHOW_DELAY = 120000; // 2 min
    const CONFIRMATION_CLOSE_DELAY = 10000; // 10 s

    let autoTimer = null;
    let isInteracting = false;
    let confirmationHandled = false;

    // AUTO EJECT AFTER 2 MIN
    autoTimer = setTimeout(() => {
        if (!isInteracting && !confirmationHandled) {
            widget.classList.add('is-open');
        }
    }, AUTO_SHOW_DELAY);

    // USER INTERACTION
    widget.addEventListener('mouseenter', () => {
        if (confirmationHandled) return;
        isInteracting = true;
        widget.classList.add('is-open');
    });

    widget.addEventListener('mouseleave', () => {
        if (confirmationHandled) return;
        isInteracting = false;
        widget.classList.remove('is-open');
    });

    widget.addEventListener('focusin', () => {
        if (confirmationHandled) return;
        isInteracting = true;
        widget.classList.add('is-open');
    });

    widget.addEventListener('focusout', () => {
        if (confirmationHandled) return;
        isInteracting = false;
        widget.classList.remove('is-open');
    });

    // RADIO → CLASSES is-1 … is-5
    document.addEventListener('change', function (e) {
        if (!e.target.matches('#pweStore .store-feedback input[type="radio"]')) return;

        widget.classList.remove('is-1', 'is-2', 'is-3', 'is-4', 'is-5');
        widget.classList.add('is-' + e.target.value);
    });

    // CONFIRMATION SERVICE (GF)
    const observer = new MutationObserver(() => {
        if (confirmationHandled || !widget.querySelector('.gform_confirmation_wrapper')) {
            return;
        }

        confirmationHandled = true;

        // keep the widget open
        widget.classList.add('is-open');

        setTimeout(() => {
            widget.classList.remove('is-open');
        }, CONFIRMATION_CLOSE_DELAY);

        // end the observation – only once
        observer.disconnect();
    });

    observer.observe(widget, {
        childList: true,
        subtree: true,
    });
})();

(function () {

  function isPolish() {
    const lang = document.documentElement.lang || '';
    return lang.toLowerCase().startsWith('pl');
  }

  function replaceStoreFeedbackTexts() {
    if (isPolish()) return;

    // Label
    document.querySelectorAll(
      '.store-feedback .gform-field-label'
    ).forEach(el => {
      if (el.textContent.trim() === 'Jeśli masz dodatkowe uwagi, daj nam znać') {
        el.textContent = 'If you have any additional comments, please let us know.';
      }
    });

    // Submit button
    document.querySelectorAll(
      '.store-feedback input.gform_button'
    ).forEach(btn => {
      if (btn.value === 'Wyślij') {
        btn.value = 'Send';
      }
    });
  }

  function replaceConfirmation() {
    if (isPolish()) return;

    document.querySelectorAll(
      '.store-feedback .gform_confirmation_message'
    ).forEach(conf => {
      if (conf.textContent.trim() === 'Dziękujemy za przesłanie opinii.') {
        conf.textContent = 'Thank you for submitting your feedback.';
      }
    });
  }

  // DOM ready
  document.addEventListener('DOMContentLoaded', replaceStoreFeedbackTexts);

  // AJAX submit confirmation 
  jQuery(document).on('gform_confirmation_loaded', function (event, formId) {
    replaceConfirmation();
  });
})();

document.addEventListener('DOMContentLoaded', function () {
    const sourceField = document.querySelector('input[name="input_3"]');
    if (!sourceField) return;

    if (window.location.pathname.includes('/katalog') || window.location.pathname.includes('/exhibitors-catalog')) {
        sourceField.value = 'catalog';
    } else if (window.location.pathname.includes('/sklep') || window.location.pathname.includes('/store')) {
        sourceField.value = 'shop';
    }
});