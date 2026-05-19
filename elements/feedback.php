<?php

/**
 * Class PWElementFeedback
 * Extends PWElements class and defines a pwe Visual Composer element for vouchers.
 */
class PWElementFeedback extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Static method to initialize Visual Composer elements.
     * Returns an array of parameters for the Visual Composer element.
     */
    public static function initElements() {
        $element_output = array(

        );

        return $element_output;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public static function output($atts) {

        /**
         * Get Gravity Form ID by title with DB cache
         */
        if (!function_exists('ec_get_form_id')) {
            function ec_get_form_id($form_title) {
                if (!class_exists('GFAPI')) {
                    return null;
                }

                $option_key = 'feedback_gf_form_id';

                $cached_id = get_option($option_key);

                if ($cached_id) {
                    $form = GFAPI::get_form($cached_id);

                    if ($form && empty($form['is_trash']) && (!isset($form['is_active']) || $form['is_active'] === 1)) {
                        return $cached_id;
                    }

                    delete_option($option_key);
                }

                $forms = GFAPI::get_forms();

                foreach ($forms as $form) {
                    if (isset($form['title']) && trim(mb_strtolower($form['title'])) === trim(mb_strtolower($form_title))) {
                        update_option($option_key, $form['id'], true);
                        return $form['id'];
                    }
                }

                return null;
            }
        }

        $form_title = 'User opinions';
        $form_id = ec_get_form_id($form_title);

        if (!function_exists('gravity_form') || !$form_id) {
            return;
        }

        $request_uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = trim(parse_url($request_uri, PHP_URL_PATH), '/');

        $source = $path === '' ? 'home' : $path;

        $version = 'php';


        ob_start();
        gravity_form($form_id, false, false, false, null, true);
        $form_html = ob_get_clean();
        $opinion_type = self::languageChecker('katalog', 'catalog');

        $output = '';

        if($_SERVER['HTTP_HOST'] == "warsawexpo.eu"){
            $opinion_type = self::languageChecker('kalendarz', 'calendar');
        }

        $output .= '
        <style>
            .row-container:has(.pwe-feedback) .row-parent {
                padding: 0 !important;
            }
            #PweFeedback {
                position: fixed;
                bottom: 10px;
                right: 66px;
                z-index: 20;
                width: 260px;
                height: auto;
                background: #fffffe;
                box-shadow: 0 0 12px -8px black;
                border-radius: 18px;
                padding: 14px;
                transform: translateY(104%);
                transition: 0.5s ease;
            }

            #PweFeedback:has(.gform_confirmation_wrapper) {
                transform: translateY(56px);
                text-align: center;
            }

            #PweFeedback.is-open {
                transform: translateY(0);
            }

            #PweFeedback.is-open:has(.gform_confirmation_wrapper) {
                transform: translateY(0);
            }

            #PweFeedback.is-1 {
                background: #fff5f5;
            }

            #PweFeedback.is-2 {
                background: #fff7ed;
            }

            #PweFeedback.is-3 {
                background: #fafafa;
            }

            #PweFeedback.is-4 {
                background: #f0fdf4;
            }

            #PweFeedback.is-5 {
                background: #eafee8;
            }

            .pwe-feedback__toggle {
                all: unset;
                cursor: pointer;
                position: absolute;
                top: -34px;
                right: 34px;
            }

            #PweFeedback svg {
                width: 38px;
            }

            .pwe-feedback__close {
                all: unset;
                cursor: pointer;
                position: absolute;
                top: 12px;
                right: 12px;
                font-size: 26px;
                line-height: 0.5;
                background: var(--accent-color);
                color: #fff;
                padding: 3px;
                border-radius: 50%;
                aspect-ratio: 1/1;
            }

            #PweFeedback h3 {
                margin: 0;
                margin-bottom: 12px;
                font-size: 16px;
            }

            #PweFeedback p {
                margin-top: 3px;
                font-size: 12px;
                text-align: center;
            }

            #PweFeedback .gform_fields {
                gap: 6px !important;
            }

            #PweFeedback .gfield_radio {
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                gap: 0;
            }

            #PweFeedback .gfield-choice-input {
                display: none;
            }

            #PweFeedback .gchoice:has(input[type="radio"]) .gform-field-label {
                font-size: 28px;
                margin: 0;
                cursor: pointer;
                filter: grayscale(1);
                transform: scale(1);
                transition: 0.3s ease;
            }

            #PweFeedback .gchoice:has(input[type="radio"]) .gform-field-label:hover {
                filter: grayscale(0);
                transform: scale(1.05);
            }

            #PweFeedback .gchoice:has(input[type="radio"]:checked) .gform-field-label {
                filter: grayscale(0);
                transform: scale(1.15);
            }

            #PweFeedback .gfield_label {
                font-size: 13px;
            }

            #PweFeedback textarea {
                max-height: 80px;
                min-block-size: 2rem;
            }

            #PweFeedback .gform_footer {
                margin-top: 10px !important;
                margin-bottom: 10px !important;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            #PweFeedback .gform_footer input[type="submit"] {
                background: var(--accent-color);
                font-weight: 700 !important;
                border-radius: 36px;
                width: 100%;
                transition: 0.3s ease;
            }

            #PweFeedback .gform_footer input[type="submit"]:hover {
                background: var(--accent_dark_color);
            }
            #PweFeedback:has(.gform_confirmation_wrapper) h3,
            #PweFeedback:has(.gform_confirmation_wrapper) .rating-hint,
            #PweFeedback:has(.gform_confirmation_wrapper) svg {
                display: none;
            }
            #PweFeedback .gform_confirmation_message {
                font-size: 14px;
            }

            #PweFeedback:not(.is-open) .pwe-feedback__toggle {
                animation: feedback-bounce 1.6s ease-in-out infinite;
            }

            #PweFeedback:not(.is-open):hover .pwe-feedback__toggle,
            #PweFeedback:not(.is-open):focus-within .pwe-feedback__toggle {
                animation-play-state: paused;
            }

            #PweFeedback:has(.gform_confirmation_wrapper) .pwe-feedback__close {
                display: none;
            }

            @keyframes feedback-bounce {
                0% {
                    transform: translateY(0);
                }
                30% {
                    transform: translateY(-6px);
                }
                50% {
                    transform: translateY(0);
                }
                70% {
                    transform: translateY(-3px);
                }
                100% {
                    transform: translateY(0);
                }
            }

        </style>

        <div id="PweFeedback" class="pwe-feedback">
            <button class="pwe-feedback__toggle" aria-label="Otwórz opinię">
                <svg fill="var(--accent-color)" viewBox="4 4 16 16" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g data-name="Layer 2"> <g data-name="arrow-up"> <rect width="24" height="24" transform="rotate(90 12 12)" opacity="0"></rect> <path d="M16.21 16H7.79a1.76 1.76 0 0 1-1.59-1 2.1 2.1 0 0 1 .26-2.21l4.21-5.1a1.76 1.76 0 0 1 2.66 0l4.21 5.1A2.1 2.1 0 0 1 17.8 15a1.76 1.76 0 0 1-1.59 1z"></path> </g> </g> </g></svg>
            </button>
            <button class="pwe-feedback__close" aria-label="Zamknij opinię">×</button>
            <h3>'.self::languageChecker('Jak oceniasz ', 'How would you rate ').' ' .$opinion_type. '?</h3>
            ' . $form_html . '
            <p class="rating-hint">'.self::languageChecker('Twoja opinia jest anonimowa.', 'Your opinion is anonymous.').'</p>
        </div>

        <script>
            (function () {
                const widget = document.querySelector("#PweFeedback");

                if (!widget) return;

                const toggleBtn = widget.querySelector(".pwe-feedback__toggle");
                const closeBtn = widget.querySelector(".pwe-feedback__close");

                const AUTO_SHOW_DELAY = 120000;
                const CONFIRMATION_CLOSE_DELAY = 10000;

                let confirmationHandled = false;

                /* ===============================
                TOGGLE OPEN / CLOSE
                =============================== */
                const open = () => widget.classList.add("is-open");
                const close = () => widget.classList.remove("is-open");

                toggleBtn.addEventListener("click", (e) => {
                    e.stopPropagation();
                    widget.classList.toggle("is-open");
                });

                closeBtn.addEventListener("click", (e) => {
                    e.stopPropagation();
                    close();
                });

                /* ===============================
                KLIK POZA WIDGET
                =============================== */
                document.addEventListener("click", (e) => {
                    if (!widget.contains(e.target)) {
                        close();
                    }
                });

                /* ===============================
                AUTO WYSUNIĘCIE PO 2 MIN
                =============================== */
                setTimeout(() => {
                    if (!confirmationHandled) {
                        open();
                    }
                }, AUTO_SHOW_DELAY);

                /* ===============================
                RADIO → KLASY is-1 … is-5
                =============================== */
                document.addEventListener("change", (e) => {
                    if (!e.target.matches("#PweFeedback input[type=`radio`]")) return;

                    widget.classList.remove("is-1", "is-2", "is-3", "is-4", "is-5");
                    widget.classList.add("is-" + e.target.value);
                });

                /* ===============================
                CONFIRMATION (Gravity Forms)
                =============================== */
                const observer = new MutationObserver(() => {
                    if (confirmationHandled || !widget.querySelector(".gform_confirmation_wrapper")) return;

                    confirmationHandled = true;
                    open();

                    setTimeout(close, CONFIRMATION_CLOSE_DELAY);
                    observer.disconnect();
                });

                observer.observe(widget, {
                    childList: true,
                    subtree: true,
                });
            })();

            document.addEventListener("DOMContentLoaded", function () {

                const widget = document.querySelector("#PweFeedback");
                if (!widget) return;

                const sourceField  = widget.querySelector("input[name=\'input_3\']");
                const widthField   = widget.querySelector("input[name=\'input_4\']");
                const versionField = widget.querySelector("input[name=\'input_5\']");

                if (sourceField) {
                    sourceField.value = ' . json_encode($source) . ';
                }

                if (versionField) {
                    versionField.value = ' . json_encode($version) . ';
                }

                if (!widthField) return;

                function getDeviceWidth() {
                    return Math.max(
                        document.documentElement.clientWidth || 0,
                        window.innerWidth || 0
                    );
                }

                const width = getDeviceWidth();
                let device = "desktop";

                if (width <= 762) device = "mobile";
                else if (width <= 1024) device = "tablet";

                widthField.value = device + " " + width + "px";
            });

            </script>';
            if(get_locale() !=="pl_PL"){
            $output .= '
            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    const widget = document.getElementById("PweFeedback");
                    if (!widget) return;

                    const translations = {
                        "Jeśli masz dodatkowe uwagi, daj nam znać": "If you have additional comments, let us know",
                        "Wyślij": "Send",
                        "Dziękujemy za przesłanie opinii.": "Thank you for submitting your feedback."
                    };

                    function translateTextNodes(node) {
                        node.childNodes.forEach(child => {
                            if (child.nodeType === Node.TEXT_NODE) {
                                const trimmed = child.textContent.trim();
                                if (translations[trimmed]) {
                                    child.textContent = child.textContent.replace(trimmed, translations[trimmed]);
                                }
                            } else if (child.nodeType === Node.ELEMENT_NODE) {
                                // Tłumaczenie submit button
                                if (child.tagName === "INPUT" && child.type === "submit") {
                                    const val = child.value.trim();
                                    if (translations[val]) child.value = translations[val];
                                }
                                translateTextNodes(child);
                            }
                        });
                    }

                    translateTextNodes(widget);

                    const observer = new MutationObserver(() => {
                        translateTextNodes(widget);
                    });

                    observer.observe(widget, {
                        childList: true,
                        subtree: true
                    });
                });
            </script>';
            }
        return $output;
    }
  }