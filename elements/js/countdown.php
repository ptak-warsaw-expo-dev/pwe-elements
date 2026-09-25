<?php

/**
 * Class PWECountdown for display countdown timer
 *
 */
class PWECountdown {

    /**
     * Constructor method.
     */
    public function __construct() {
    }

    /**
     * Read translations from JSON and return a single translation by key.
     * If no translation found for current locale, fallback to en_US.
     */
    public static function multi_translation($key) {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../../translations/elements/countdown.json';

        if (!file_exists($translations_file)) {
            // fallback: return key if file missing
            return $key;
        }

        $translations_data = json_decode(file_get_contents($translations_file), true);
        if (!is_array($translations_data)) {
            return $key;
        }

        if (isset($translations_data[$locale])) {
            $translations_map = $translations_data[$locale];
        } else {
            $translations_map = isset($translations_data['en_US']) ? $translations_data['en_US'] : [];
        }

        return isset($translations_map[$key]) ? $translations_map[$key] : $key;
    }

    /**
     * Static method for counting down time
     *
     * @param array $timer for countdown data
     * @param string $target_id script target countdown id
     */
    private static function countingDown($timer, $target_id = '',  $options = []) {

        $showShort = !empty($options['show_short_name_data']);
        $hide_seconds = !empty($options['hide_seconds']);

        $mobile = preg_match('/Mobile|Android|iPhone/i', isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
        $local = get_locale();

        // Helper to try multiple translation keys and fallback sensibly
        $mt = function($k) {
            $v = self::multi_translation($k);
            return ($v === $k) ? null : $v;
        };

        // Build singular/plural pairs for German (or any language where you add specific keys)
        $day_singular = $mt('day_singular') ?? $mt('day_one') ?? $mt('day') ?? 'day';
        $day_plural   = $mt('day_plural')   ?? $mt('day_two') ?? $mt('day_five') ?? ($day_singular . 's');

        $hour_singular = $mt('hour_singular') ?? $mt('hour_one') ?? $mt('hour') ?? 'hour';
        $hour_plural   = $mt('hour_plural')   ?? $mt('hour_two') ?? $mt('hour_five') ?? ($hour_singular . 's');

        $minute_singular = $mt('minute_singular') ?? $mt('minute_one') ?? $mt('minute') ?? 'minute';
        $minute_plural   = $mt('minute_plural')   ?? $mt('minute_two') ?? $mt('minute_five') ?? ($minute_singular . 's');

        $second_singular = $mt('second_singular') ?? $mt('second_one') ?? $mt('second') ?? 'second';
        $second_plural   = $mt('second_plural')   ?? $mt('second_two') ?? $mt('second_five') ?? ($second_singular . 's');

        // Prepare translations for JS (include both singular/plural variants)
        $translations = [
            "day_one"     => self::multi_translation('day_one'),
            "day_two"     => self::multi_translation('day_two'),
            "day_five"    => self::multi_translation('day_five'),

            "hour_one"    => self::multi_translation('hour_one'),
            "hour_two"    => self::multi_translation('hour_two'),
            "hour_five"   => self::multi_translation('hour_five'),

            "minute_one"  => self::multi_translation('minute_one'),
            "minute_two"  => self::multi_translation('minute_two'),
            "minute_five" => self::multi_translation('minute_five'),

            "second_one"  => self::multi_translation('second_one'),
            "second_two"  => self::multi_translation('second_two'),
            "second_five" => self::multi_translation('second_five'),

            // fallback single-word translations (used by English branch)
            "day"    => self::multi_translation('day'),
            "hour"   => self::multi_translation('hour'),
            "minute" => self::multi_translation('minute'),
            "second" => self::multi_translation('second'),

            // explicit singular/plural pairs (useful for German)
            "day_singular" => $day_singular,
            "day_plural"   => $day_plural,

            "hour_singular" => $hour_singular,
            "hour_plural"   => $hour_plural,

            "minute_singular" => $minute_singular,
            "minute_plural"   => $minute_plural,

            "second_singular" => $second_singular,
            "second_plural"   => $second_plural,

            // other UI translations used in JS
            "register_text" => self::multi_translation('register_text'),
            "register_link" => self::multi_translation('register_link'),
            "countdown_btn_text" => self::multi_translation('countdown_btn_text'),
            "countdown_btn_url"  => self::multi_translation('countdown_btn_url')
        ];

        $translations_js = json_encode($translations);

        // determine if seconds should be shown (boolean) and pass it to JS
        $showSeconds = (!$mobile && !$hide_seconds && !$showShort);

        if(!$showShort && $target_id != "") {
            // Primary (full) timer output
            echo '
            <script>
            (function(){
                const timer = ' . json_encode($timer) . ';
                // normalize datetime strings
                for (let i = 0; i < timer.length; i++) {
                    if (timer[i] && timer[i]["countdown_end"]) {
                        timer[i]["countdown_end"] = timer[i]["countdown_end"].replace(/\\//g, "-").replace(" ", "T");
                    }
                }

                const TR = ' . $translations_js . ';
                const locale = ' . json_encode($local) . ';
                const showSeconds = ' . json_encode($showSeconds) . ';
                const targetId = ' . json_encode($target_id) . ';

                jQuery(document).ready(function($) {
                    const intervals = {};
                    let j = 0; // pointer to current timer item

                    function updateCountdownStop(elementId) {
                        if (intervals[elementId]) {
                            clearInterval(intervals[elementId]);
                            delete intervals[elementId];
                        }
                    }

                    function pluralizePolish(count, singular, plural, pluralGenitive) {
                        if (count === 1 || (count % 10 === 1 && count % 100 !== 11)) {
                            return count + " " + singular;
                        } else if (count % 10 >= 2 && count % 10 <= 4 && (count % 100 < 10 || count % 100 >= 20)) {
                            return count + " " + plural;
                        } else {
                            return count + " " + pluralGenitive;
                        }
                    }

                    function pluralizeEnglish(count, noun) {
                        return count + " " + (count === 1 ? noun : (noun + "s"));
                    }

                    function pluralizeGerman(count, singular, plural) {
                        return count + " " + (count === 1 ? singular : plural);
                    }

                    // Wrapper using TR translations
                    function buildEndMessage(days, hours, minutes, seconds) {
                        if (locale === "pl_PL") {
                            const d = pluralizePolish(days, TR.day_one, TR.day_two, TR.day_five);
                            const h = pluralizePolish(hours, TR.hour_one, TR.hour_two, TR.hour_five);
                            const m = pluralizePolish(minutes, TR.minute_one, TR.minute_two, TR.minute_five);
                            const s = showSeconds ? " " + pluralizePolish(seconds, TR.second_one, TR.second_two, TR.second_five) : "";
                            return d + " " + h + " " + m + s;
                        } else if (locale === "de_DE") {
                            const d = pluralizeGerman(days, TR.day_singular, TR.day_plural);
                            const h = pluralizeGerman(hours, TR.hour_singular, TR.hour_plural);
                            const m = pluralizeGerman(minutes, TR.minute_singular, TR.minute_plural);
                            const s = showSeconds ? " " + pluralizeGerman(seconds, TR.second_singular, TR.second_plural) : "";
                            return d + " " + h + " " + m + s;
                        } else {
                            const d = pluralizeEnglish(days, TR.day);
                            const h = pluralizeEnglish(hours, TR.hour);
                            const m = pluralizeEnglish(minutes, TR.minute);
                            const s = showSeconds ? " " + pluralizeEnglish(seconds, TR.second) : "";
                            return d + " " + h + " " + m + s;
                        }
                    }

                    function updateCountdown(elementId) {
                        // ensure we clear previous interval if exists
                        updateCountdownStop(elementId);

                        intervals[elementId] = setInterval(function() {
                            if (typeof timer[j] !== "undefined" && timer[j] != null) {
                                const rightNow = new Date();
                                const endTime = new Date(timer[j]["countdown_end"]);
                                // keep hours as-is; in case you need timezone adjustments, handle here
                                endTime.setHours(endTime.getHours());
                                const distance = endTime - rightNow;

                                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                let endMessage = "";

                                if (distance < 0) {
                                    // move to next timer entry
                                    j++;
                                    if (typeof timer[j] !== "undefined" && timer[j] != null && timer[j]["countdown_text"] != "") {
                                        $("#timer-header-text-" + elementId).text(timer[j]["countdown_text"]);
                                        $("#timer-button-" + elementId).text(timer[j]["countdown_btn_text"]);
                                        $("#timer-button-" + elementId).attr("href", timer[j]["countdown_btn_url"]);
                                    }
                                } else {
                                    endMessage = buildEndMessage(days, hours, minutes, seconds);
                                    $("#pwe-countdown-timer-" + elementId).text(endMessage);
                                }
                            } else {
                                // no more timers; stop and hide
                                updateCountdownStop(elementId);
                                $("#pwe-countdown-timer-" + elementId).parent().hide(0);
                            }
                        }, 1000);
                    }

                    // start
                    updateCountdown(targetId);

                    // Change button on sticky main timer (existing logic)
                    function handleClassChange(mutationsList, observer) {
                        for (let mutation of mutationsList) {
                            if (mutation.type === "attributes" && mutation.attributeName === "class") {
                                const targetElement = mutation.target;
                                const customBtn = document.getElementById("timer-button-" + targetId);
                                const hasStuckedClass = targetElement.classList.contains("is_stucked");
                                if (customBtn) {
                                    const buttonLink = customBtn.href || "";
                                    if (hasStuckedClass) {
                                        // use translations from TR
                                        customBtn.innerHTML = TR.register_text || \'<span>Zarejestruj się<br/>Odbierz darmowy bilet</span>\';
                                        customBtn.href = TR.register_link || "/rejestracja/";
                                    } else {
                                        customBtn.innerHTML = "<span>" + (TR.countdown_btn_text || "Zostań wystawcą") + "</span>";
                                        customBtn.href = TR.countdown_btn_url || "/zostan-wystawca/";
                                    }
                                }
                            }
                        }
                    }

                    let is_stucked = false;
                    const targetElement = document.querySelector(".sticky-element");
                    const mainTimerElement = document.querySelector("#main-timer");
                    const observer = new MutationObserver(handleClassChange);

                    if (mainTimerElement) {
                        const config = { attributes: true, attributeFilter: ["class"] };
                        const showRegisterBarValue = mainTimerElement.getAttribute("data-show-register-bar");
                        if (targetElement && showRegisterBarValue !== "true") {
                            observer.observe(targetElement, config);
                            targetElement.setAttribute("data-is-stucked", is_stucked);
                        }
                    }
                });
            })();
            </script>
            ';
        } else if($target_id != "") {
            // Compact/minimal timer (icons like d/h/m)
            echo '
            <script>
            (function(){
                const timer = ' . json_encode($timer) . ';
                for (let i = 0; i < timer.length; i++) {
                    if (timer[i] && timer[i]["countdown_end"]) {
                        timer[i]["countdown_end"] = timer[i]["countdown_end"].replace(/\\//g, "-").replace(" ", "T");
                    }
                }

                const TR = ' . $translations_js . ';
                const locale = ' . json_encode($local) . ';
                const showSeconds = ' . json_encode($showSeconds) . ';
                const targetId = ' . json_encode($target_id) . ';

                jQuery(document).ready(function($) {
                    const intervals = {};
                    let j = 0;

                    function updateCountdownStop(elementId) {
                        if (intervals[elementId]) {
                            clearInterval(intervals[elementId]);
                            delete intervals[elementId];
                        }
                    }

                    function pluralizePolish(count, singular, plural, pluralGenitive) {
                        if (count === 1 || (count % 10 === 1 && count % 100 !== 11)) {
                            return count + " " + singular;
                        } else if (count % 10 >= 2 && count % 10 <= 4 && (count % 100 < 10 || count % 100 >= 20)) {
                            return count + " " + plural;
                        } else {
                            return count + " " + pluralGenitive;
                        }
                    }

                    function pluralizeEnglish(count, noun) {
                        return count + " " + (count === 1 ? noun : (noun + "s"));
                    }

                    function pluralizeGerman(count, singular, plural) {
                        return count + " " + (count === 1 ? singular : plural);
                    }

                    function buildEndMessageCompact(days, hours, minutes, seconds) {
                        if (locale === "pl_PL") {
                            const d = pluralizePolish(days, TR.day_one, TR.day_two, TR.day_five);
                            const h = pluralizePolish(hours, TR.hour_one, TR.hour_two, TR.hour_five);
                            const m = pluralizePolish(minutes, TR.minute_one, TR.minute_two, TR.minute_five);
                            const s = showSeconds ? " " + pluralizePolish(seconds, TR.second_one, TR.second_two, TR.second_five) : "";
                            return d + " " + h + " " + m + s;
                        } else if (locale === "de_DE") {
                            const d = pluralizeGerman(days, TR.day_singular, TR.day_plural);
                            const h = pluralizeGerman(hours, TR.hour_singular, TR.hour_plural);
                            const m = pluralizeGerman(minutes, TR.minute_singular, TR.minute_plural);
                            const s = showSeconds ? " " + pluralizeGerman(seconds, TR.second_singular, TR.second_plural) : "";
                            return d + " " + h + " " + m + s;
                        } else {
                            const d = pluralizeEnglish(days, TR.day);
                            const h = pluralizeEnglish(hours, TR.hour);
                            const m = pluralizeEnglish(minutes, TR.minute);
                            const s = showSeconds ? " " + pluralizeEnglish(seconds, TR.second) : "";
                            return d + " " + h + " " + m + s;
                        }
                    }

                    function updateCountdown(elementId) {
                        updateCountdownStop(elementId);

                        intervals[elementId] = setInterval(function() {
                            if (typeof timer[j] !== "undefined" && timer[j] != null) {
                                const rightNow = new Date();
                                const endTime = new Date(timer[j]["countdown_end"]);
                                endTime.setHours(endTime.getHours());
                                const distance = endTime - rightNow;

                                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                if (distance < 0) {
                                    j++;
                                    if (typeof timer[j] !== "undefined" && timer[j] != null && timer[j]["countdown_text"] != "") {
                                        $("#timer-header-text-" + elementId).text(timer[j]["countdown_text"]);
                                        $("#timer-button-" + elementId).text(timer[j]["countdown_btn_text"]);
                                        $("#timer-button-" + elementId).attr("href", timer[j]["countdown_btn_url"]);
                                    }
                                } else {
                                    const endMessage = buildEndMessageCompact(days, hours, minutes, seconds);
                                    $("#pwe-countdown-timer-" + elementId).text(endMessage);
                                }
                            } else {
                                updateCountdownStop(elementId);
                                $("#pwe-countdown-timer-" + elementId).parent().hide(0);
                            }
                        }, 1000);
                    }

                    updateCountdown(targetId);

                    // sticky button logic reused
                    function handleClassChange(mutationsList, observer) {
                        for (let mutation of mutationsList) {
                            if (mutation.type === "attributes" && mutation.attributeName === "class") {
                                const targetElement = mutation.target;
                                const customBtn = document.getElementById("timer-button-" + targetId);
                                const hasStuckedClass = targetElement.classList.contains("is_stucked");
                                if (customBtn) {
                                    const buttonLink = customBtn.href || "";
                                    if (hasStuckedClass) {
                                        // use translations from TR
                                        customBtn.innerHTML = TR.register_text || \'<span>Zarejestruj się<br/>Odbierz darmowy bilet</span>\';
                                        customBtn.href = TR.register_link || "/rejestracja/";
                                    } else {
                                        customBtn.innerHTML = "<span>" + (TR.countdown_btn_text || "Zostań wystawcą") + "</span>";
                                        customBtn.href = TR.countdown_btn_url || "/zostan-wystawca/";
                                    }
                                }
                            }
                        }
                    }

                    let is_stucked = false;
                    const targetElement = document.querySelector(".sticky-element");
                    const mainTimerElement = document.querySelector("#main-timer");
                    const observer = new MutationObserver(handleClassChange);

                    if (mainTimerElement) {
                        const config = { attributes: true, attributeFilter: ["class"] };
                        const showRegisterBarValue = mainTimerElement.getAttribute("data-show-register-bar");
                        if (targetElement && showRegisterBarValue !== "true") {
                            observer.observe(targetElement, config);
                            targetElement.setAttribute("data-is-stucked", is_stucked);
                        }
                    }
                });
            })();
            </script>
            ';
        }

    }

    /**
     * Static method to generate the HTML output for the PWE Countdown.
     *
     * @param array $countdown for countdown data
     * @param string $timer_id script target countdown id
     */
    public static function output($countdown, $timer_id, $options = []) {
        // countingDown echoes the script directly
        self::countingDown($countdown, $timer_id, $options);
    }
}
