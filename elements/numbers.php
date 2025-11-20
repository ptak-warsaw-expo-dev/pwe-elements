<?php

/**
 * Class PWElementNumbers
 * Extends PWElements class and defines a pwe Visual Composer element.
 */
class PWElementNumbers extends PWElements {

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
            array(
                'type' => 'dropdown',
                'group' => 'PWE Element',
                'heading' => __('Mode', 'pwe_element'),
                'param_name' => 'pwe_numbers_mode',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementNumbers',
                ),
                'value' => array(
                    'Simple mode' => 'simple_mode',
                    'Footer mode' => 'footer_mode',
                    'Footer new mode' => 'footer_new_mode',
                ),

            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Turn on footer section', 'pwe_display_info'),
                'param_name' => 'pwe_numbers_footer_section',
                'save_always' => true,
                'value' => array(__('True', 'pwe_display_info') => 'true',),
                'dependency' => array(
                    'element' => 'pwe_numbers_mode',
                    'value' => 'footer_mode',
                ),
            ),
            array(
                'type' => 'dropdown',
                'group' => 'PWE Element',
                'heading' => __('Icons color <a href="#" onclick="yourFunction(`pwe_number_color_icons_hidden`, `pwe_number_color_icons`)">CUSTOM</a>', 'pwe_element'),
                'param_name' => 'pwe_number_color_icons',
                'description' => __('Specify the thumbnail width for desktop.', 'pwe_element'),
                'save_always' => true,
                'value' => self::$fair_colors,
                'dependency' => array(
                    'element' => 'pwe_number_color_icons_hidden',
                    'value' => array(''),
                    'callback' => "hideEmptyElem",
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Icons color <a href="#" onclick="yourFunction(`pwe_number_color_icons`, `pwe_number_color_icons_hidden`)">SELECT</a>', 'pwe_element'),
                'param_name' => 'pwe_number_color_icons_hidden',
                'param_holder_class' => 'pwe_dependent-hidden',
                'value' => '',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_numbers_mode',
                    'value' => 'simple_mode',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Custom title first', 'pwe_element'),
                'param_name' => 'pwe_custom_title_first',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_numbers_mode',
                    'value' => 'simple_mode',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Number first', 'pwe_element'),
                'param_name' => 'pwe_number_first',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_numbers_mode',
                    'value' => 'simple_mode',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Custom title second', 'pwe_element'),
                'param_name' => 'pwe_custom_title_second',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_numbers_mode',
                    'value' => 'simple_mode',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Number second', 'pwe_element'),
                'param_name' => 'pwe_number_second',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_numbers_mode',
                    'value' => 'simple_mode',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Custom title third', 'pwe_element'),
                'param_name' => 'pwe_custom_title_third',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_numbers_mode',
                    'value' => 'simple_mode',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Number third', 'pwe_element'),
                'param_name' => 'pwe_number_third',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_numbers_mode',
                    'value' => 'simple_mode',
                ),
            ),
        );
        return $element_output;
    }

    public static function multi_translation($key) {
        $locale = get_locale();
        $translations_file = __DIR__ . '/../translations/elements/numbers.json';

        // JSON file with translation
        $translations_data = json_decode(file_get_contents($translations_file), true);

        // Is the language in translations
        if (isset($translations_data[$locale])) {
            $translations_map = $translations_data[$locale];
        } else {
            // By default use English translation if no translation for current language
            $translations_map = $translations_data['en_US'];
        }

        // Return translation based on key
        return isset($translations_map[$key]) ? $translations_map[$key] : $key;
    }

    public static function output($atts) {
        $pwe_number_color_icons_hidden = isset($atts['pwe_number_color_icons_hidden']) ? $atts['pwe_number_color_icons_hidden'] : null;
        $pwe_number_color_icons = self::findColor($pwe_number_color_icons_hidden, $atts['pwe_number_color_icons'], self::$accent_color);

        extract( shortcode_atts( array(
            'pwe_numbers_mode' => '',
            'pwe_numbers_footer_section' => '',
            'pwe_custom_title_first' => '',
            'pwe_number_first' => '',
            'pwe_custom_title_second' => '',
            'pwe_number_second' => '',
            'pwe_custom_title_third' => '',
            'pwe_number_third' => '',
        ), $atts ));

        if ($pwe_numbers_mode == "simple_mode") {

            $pwe_custom_title_first = (empty($pwe_custom_title_first)) ? ''. self::multi_translation("days") .'' : $pwe_custom_title_first;
            $pwe_custom_title_second = (empty($pwe_custom_title_second)) ? ''. self::multi_translation("speakers") .'' : $pwe_custom_title_second;
            $pwe_custom_title_third = (empty($pwe_custom_title_third)) ? ''. self::multi_translation("participants") .'' : $pwe_custom_title_third;

            $output = '
            <style>
                .pwelement_'. self::$rnd_id .' .pwe-numbers-row {
                    width: 100%;
                    text-align: center;
                }
                .pwelement_'. self::$rnd_id .' .pwe-numbers {
                    width: 100%;
                    display: flex;
                    justify-content: center;
                    gap: 36px;
                }
                .pwelement_'. self::$rnd_id .' .pwe-numbers-item {
                    width: 33%;
                }
                .pwe-numbers-item-icon i {
                    color: '. $pwe_number_color_icons .' !important;
                }
                .pwelement_'. self::$rnd_id .' .pwe-numbers-item-heading h3 {
                    margin: 24px auto 0;
                }
                @media (max-width:768px) {
                    .pwelement_'. self::$rnd_id .' .pwe-numbers {
                        gap: 18px;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-numbers-item-heading h3 {
                        font-size: 16px;
                    }
                }
                @media (max-width:500px) {
                    .pwelement_'. self::$rnd_id .' .pwe-numbers {
                        flex-direction: column;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-numbers-item {
                        width: 100%;
                    }
                    .pwelement_'. self::$rnd_id .' .pwe-numbers-item-heading h3 {
                        font-size: 20px;
                    }
                }
            </style>

            <div id="pweNumbers"class="pwe-container-numbers">
                <div class="pwe-numbers-row">

                    <div class="pwe-numbers">

                        <div class="pwe-numbers-item">
                            <div class="pwe-numbers-item-icon">
                                <i class="fa fa-calendar fa-4x fa-fw"></i>
                            </div>
                            <div class="pwe-numbers-item-heading">
                                <h3>'. $pwe_number_first .' '. $pwe_custom_title_first .'</h3>
                            </div>
                        </div>
                        <div class="pwe-numbers-item">
                            <div class="pwe-numbers-item-icon">
                                <i class="fa fa-users2 fa-4x fa-fw"></i>
                            </div>
                            <div class="pwe-numbers-item-heading">
                                <h3>'. $pwe_number_second .' '. $pwe_custom_title_second .'</h3>
                            </div>
                        </div>
                        <div class="pwe-numbers-item">
                            <div class="pwe-numbers-item-icon">
                                <i class="fa fa-group fa-4x fa-fw"></i>
                            </div>
                            <div class="pwe-numbers-item-heading">
                                <h3>'. $pwe_number_third .' '. $pwe_custom_title_third .'</h3>
                            </div>
                        </div>

                    </div>

                </div>
            </div>';

        } else if ($pwe_numbers_mode == "footer_mode") {
            $output = '
            <style>
                .pwe-numbers {
                    max-width:1200px;
                    margin: 0 auto;
                    display: flex;
                    flex-direction: column;
                    gap:30px;
                }
                .pwe-numbers__title {
                    margin: 0 auto;
                    font-size: 24px !important;
                    text-align: center;
                    text-transform: uppercase;
                }
                .pwe-numbers__wrapper {
                    display: flex;
                    gap:30px;
                }
                .pwe-numbers__img {
                    flex: .5;
                    background-image: url(/wp-content/plugins/pwe-media/media/bg-object.jpg);
                    background-repeat: no-repeat;
                    background-size: cover;
                    background-position: center;
                    width: 100%;
                    border-radius: 30px;
                }
                .pwe-numbers__container {
                    flex:.5;
                }
                .pwe-numbers__container-ufi {
                    display: flex;
                    justify-content: space-around;
                    border:1px solid rgba(0, 0, 0, 0.1);
                    border-radius: 30px;
                    margin-bottom: 20px;
                    max-width: 90%;
                    margin:0 auto;
                }
                .pwe-numbers__container-ufi img {
                    max-width: 45%;
                }
                .pwe-numbers__container-numbers {
                    display:flex;
                    justify-content: space-around;
                }
                .pwe-numbers__container-numbers div {
                    flex:.5;
                    display: flex;
                    align-items: center;
                    flex-direction: column;
                    margin:10px 0px;
                }
                .pwe-numbers__container-numbers img {
                    max-height: 50px;
                    object-fit: contain;
                }
                .pwe-numbers__container-numbers h3, .pwe-numbers__container-numbers p {
                    margin:4px 0;
                    line-height: 1.3;
                    text-align: center;
                }
                @media(max-width:900px){
                    .pwe-numbers__wrapper {
                        flex-direction: column;
                    }
                    .pwe-numbers__img {
                        min-height: 250px;
                    }
                }';
                if ($pwe_numbers_footer_section != true) {
                    $output .= '
                        .pwe-footer-bg,
                        .pwe-footer-images-bg {
                            display: none;
                        }
                    ';
                }
                $output .= '
            </style>

            <div id="pweNumbers" class="pwe-numbers">
                <h2 class="pwe-numbers__title">'. self::multi_translation("footer_text") .'</h2>
                <div class="pwe-numbers__wrapper">
                    <div class="pwe-numbers__container">
                        <div class="pwe-numbers__container-ufi">
                            <img src="/wp-content/plugins/pwe-media/media/numbers-el/certifed.webp" />
                            <img src="/wp-content/plugins/pwe-media/media/numbers-el/ufi.webp" />
                        </div>

                        <div class="pwe-numbers__container-numbers">
                            <div>
                                <img src="/wp-content/plugins/pwe-media/media/numbers-el/exhibitors.webp" />
                                <h3>20000</h3>
                                <p>'. self::multi_translation("exhibitors") .'</p>
                            </div>
                            <div>
                                <img src="/wp-content/plugins/pwe-media/media/numbers-el/visitors.webp" />
                                <h3>1mln+</h3>
                                <p>'. self::multi_translation("visitors") .'</p>
                            </div>
                        </div>

                        <div class="pwe-numbers__container-numbers">
                            <div>
                                <img src="/wp-content/plugins/pwe-media/media/numbers-el/fairs.webp" />
                                <h3>140+</h3>
                                <p>'. self::multi_translation("fair") .'</p>
                            </div>
                            <div>
                                <img src="/wp-content/plugins/pwe-media/media/numbers-el/area.webp" />
                                <h3>153k</h3>
                                <p>'. self::multi_translation("surface") .'</p>
                            </div>
                        </div>
                    </div>
                    <div class="pwe-numbers__img"></div>
                </div>
            </div>';
        } else if ($pwe_numbers_mode =='footer_new_mode'){
            $output = '
            <style>
                .pwelement_'. self::$rnd_id .' .footer__top {
                    display:flex;
                    gap: 30px;
                    margin: 15px 0;
                }
                .pwelement_'. self::$rnd_id .' .footer__logos {
                    display:flex;
                    flex-direction:column;
                    flex: 0.3;
                    border: 2px solid #0000000F;
                    border-radius: 18px;
                    justify-content: space-around;
                    padding: 25px 0;
                    position: relative;
                    background-color:white !important;
                }
                .pwelement_'. self::$rnd_id .' .icon-info {
                    position: absolute;
                    top: 5px;
                    right: 5px;
                    width: 25px;
                    cursor: pointer;
                }
                .pwelement_'. self::$rnd_id .' .footer__info {
                    flex:0.7;
                    background-repeat: no-repeat;
                    background-size: contain;
                    background-position: top center;
                    border-radius: 16px;
                    aspect-ratio: auto;
                    min-height: 1px;
                    width: 100%;
                    position: relative;
                    overflow: hidden;
                }

                .pwelement_'. self::$rnd_id .' .footer__info-bg {
                    width: 100%;
                    height: auto;
                    display: block;
                }

                .pwelement_'. self::$rnd_id .' .footer__info-overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    color: white;
                    padding: 2rem;
                    background: rgba(0, 0, 0, 0.3); /* półprzezroczyste przyciemnienie, opcjonalnie */
                    display: flex;
                    gap: 1rem;
                    align-items: flex-end;
                    justify-content: space-between;
                    border-radius:18px;
                }

                .pwelement_'. self::$rnd_id .' .footer__info-title {
                    font-size: 1.8rem;
                    margin: 0;
                    color:white !important;
                    font-weight:700;
                }
                .pwelement_'. self::$rnd_id .' .footer__info-description {
                    font-weight: 500;
                    margin-top: 5px;
                    font-size: 18px;
                }
                .pwelement_'. self::$rnd_id .' .footer__calendar-link {
                    font-weight: bold;
                    text-decoration: underline;
                    cursor: pointer;
                    background-color: white;
                    color: black;
                    text-decoration: none;
                    padding: 12px 15px;
                    border-radius: 18px;
                    margin-bottom: 12px;
                }
                .pwelement_'. self::$rnd_id .' .footer__stats {
                    display:flex;
                    justify-content: space-between;
                    margin: 25px 0;
                }
                .pwelement_'. self::$rnd_id .' .footer__stat {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    width: 180px;
                    text-align: center;
                    min-height: 147px;
                }
                .pwelement_'. self::$rnd_id .' .footer__stat-value {
                    margin-top: 10px;
                    font-weight: 700;
                }
                .pwelement_'. self::$rnd_id .' .footer__stat-description {
                    margin-top: 0px;
                    font-size: 14px;
                }
                .pwelement_'. self::$rnd_id .' .footer__stat img {
                    background-color: #E7D3A24F;
                    border-radius: 100%;
                    padding: 4px 6px 6px 6px;
                    width: 55px;
                }
                .pwelement_'. self::$rnd_id .' .ufi-info-text {
                    display: none;
                    padding: 0 15px ;
                    margin-top: 0px;
                }
                .pwelement_'. self::$rnd_id .' .footer__logos.hide-images img.footer__logo {
                    display: none;
                }
                .pwelement_'. self::$rnd_id .'  .icon-close {
                    position:absolute;
                    width: 35px;
                    height: 35px;
                    top: 2px;
                    right: 2px;
                    cursor: pointer;
                    margin-left: 10px;
                }
                .pwelement_'. self::$rnd_id .' .footer__logos.show-text .ufi-info-text {
                    display: block;
                }
                .pwelement_'. self::$rnd_id .' .ufi-info-text p {
                  font-size:13px;
                  line-height:1.5;
                }
                .pwelement_'. self::$rnd_id .' .icon-info,
                .pwelement_'. self::$rnd_id .' .icon-close {
                    transition: opacity 0.3s ease;
                }

                .pwelement_'. self::$rnd_id .' .footer__logos.show-text .icon-info {
                    display: none;
                }

                .pwelement_'. self::$rnd_id .' .footer__logos:not(.show-text) .icon-close {
                    display: none;
                }
                                .pwe-footer-bg,
                .pwe-footer-images-bg {
                    display: none;
                }
                @media(max-width:620px){
                    .pwelement_'. self::$rnd_id .' .footer__logos img {
                        max-width: 300px;
                        margin: 0 auto;
                    }
                    .pwelement_'. self::$rnd_id .' .footer__top {
                        flex-direction: column;
                    }
                    .pwelement_'. self::$rnd_id .' .footer__info-overlay {
                        flex-direction: column;
                        justify-content: end;
                        align-items: center;
                        padding-bottom: 0px;
                    }
                    .pwelement_'. self::$rnd_id .' .footer__stats {
                        margin: 25px 0;
                        flex-wrap: wrap;
                        align-items: center;

                    }

                }
                @media(max-width:420px){
                    .pwelement_'. self::$rnd_id .' .footer__stat {
                        width: 140px;
                    }
                }

            </style>
            <div id="newFooter" class=".pwelement_'. self::$rnd_id .'">
                <h2 class="footer__headline">'. self::multi_translation("title") .'</h2>

                <div class="footer__top">

                    <div class="footer__logos">
                        <img class="icon-info" src="/wp-content/plugins/pwe-media/media/numbers-el/info-icon.webp"/>
                        <img src="/wp-content/plugins/pwe-media/media/numbers-el/certifed.webp" alt="Certifed" class="footer__logo" />
                        <img src="/wp-content/plugins/pwe-media/media/numbers-el/ufi.webp" alt="Ufi" class="footer__logo" />
                    </div>

                    <div class="footer__info">
                    <img src="/wp-content/plugins/pwe-media/media/stolica.webp" alt="Stolica" class="footer__info-bg" />

                    <div class="footer__info-overlay">
                        <div class="footer__info-item">
                        <h2 class="footer__info-title">'. self::multi_translation("capital") .'</h2>
                        <p class="footer__info-description">'. self::multi_translation("events") .'</p>
                        </div>
                        <a href="'. self::multi_translation("calendar_link") .'" target="_blank">
                            <div class="footer__calendar-link">'. self::multi_translation("calendar") .'</div>
                        </a>
                    </div>
                    </div>

                </div>

                <div class="footer__stats">
                    <div class="footer__stat">
                        <img src="/wp-content/plugins/pwe-media/media/numbers-el/exhibitors.webp" alt="Ikona wystawców" class="footer__stat-icon" />
                        <h2 class="footer__stat-value">20000</h2>
                        <p class="footer__stat-description">'. self::multi_translation("exhibitors") .'</p>
                    </div>
                    <div class="footer__stat">
                        <img src="/wp-content/plugins/pwe-media/media/numbers-el/visitors.webp" alt="Ikona odwiedzających" class="footer__stat-icon" />
                        <h2 class="footer__stat-value">2mln+</h2>
                        <p class="footer__stat-description">'. self::multi_translation("visitors") .'</p>
                    </div>
                    <div class="footer__stat">
                        <img src="/wp-content/plugins/pwe-media/media/numbers-el/fairs.webp" alt="Ikona targów" class="footer__stat-icon" />
                        <h2 class="footer__stat-value">140+</h2>
                        <p class="footer__stat-description">'. self::multi_translation("fair") .'</p>
                    </div>
                    <div class="footer__stat">
                        <img src="/wp-content/plugins/pwe-media/media/numbers-el/area.webp" alt="Ikona powierzchni" class="footer__stat-icon" />
                        <h2 class="footer__stat-value">153k</h2>
                        <p class="footer__stat-description">'. self::multi_translation("surface") .'</p>
                    </div>
                </div>
            </div>
            <script>
                const footer = document.querySelector(".footer__logos");
                const infoIcon = footer.querySelector(".icon-info");

                // Tworzymy X (zamknięcie), jeśli jeszcze nie istnieje
                let closeIcon = footer.querySelector(".icon-close");
                if (!closeIcon) {
                    closeIcon = document.createElement("img");
                    closeIcon.src = "/wp-content/plugins/pwe-media/media/numbers-el/close-icon.svg"; // <- podmień na swoją ikonę X
                    closeIcon.alt = "Zamknij";
                    closeIcon.className = "icon-close";
                    footer.insertBefore(closeIcon, infoIcon.nextSibling);
                }

                // Tworzymy blok tekstowy, jeśli jeszcze nie istnieje
                let infoText = footer.querySelector(".ufi-info-text");
                if (!infoText) {
                    infoText = document.createElement("div");
                    infoText.className = "ufi-info-text";
                    infoText.innerHTML = `
                    <p>'. self::multi_translation("certificate") .'</p>
                    `;
                    footer.appendChild(infoText);
                }

                // Kliknięcie w ikonę info
                infoIcon.addEventListener("click", () => {
                    footer.classList.add("hide-images", "show-text");
                });

                // Kliknięcie w ikonę X (zamknięcie)
                closeIcon.addEventListener("click", () => {
                    footer.classList.remove("hide-images", "show-text");
                });
            </script>
            ';
        }

        return $output;
    }
}