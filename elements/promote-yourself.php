<?php

/**
 * Class PWElementPromot
 * Extends PWElements class and defines a pwe Visual Composer element.
 */
class PWElementPromot extends PWElements
{

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Static method to initialize Visual Composer elements.
     * Returns an array of parameters for the Visual Composer element.
     */
    public static function initElements()
    {
        $element_output = array(
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Hide Baners To Download', 'pwelement'),
                'param_name' => 'show_banners',
                'description' => __('Check Yes to hide download options for baners.', 'pwelement'),
                'value' => '',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementPromot',
                ),
            ),
            array(
                'type' => 'checkbox',
                'group' => 'PWE Element',
                'heading' => __('Dispaly Different Logo Color', 'pwelement'),
                'param_name' => 'logo_color',
                'description' => __('Check Yes to display different logo color.', 'pwelement'),
                'value' => '',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementPromot',
                ),
            ),
        );
        return $element_output;
    }

    public static function multi_translation($key)
    {
        $locale = get_locale();
        $fallback_locale = 'en_US';

        $translations_file = __DIR__ . '/../translations/elements/promote-yourself.json';

        if (!file_exists($translations_file)) {
            return $key;
        }

        $translations_data = json_decode(file_get_contents($translations_file), true);

        if (!is_array($translations_data)) {
            return $key;
        }

        if (!isset($translations_data[$locale])) {
            $locale = $fallback_locale;
        }

        if (!isset($translations_data[$locale]) || !is_array($translations_data[$locale])) {
            return $key;
        }

        if (!array_key_exists($key, $translations_data[$locale])) {
            return $key;
        }

        $value = $translations_data[$locale][$key];

        if (is_string($value) && substr($value, -5) === '.html') {
            $html_path = __DIR__ . '/../translations/elements/promote-yourself/' . $value;

            if (file_exists($html_path)) {
                return file_get_contents($html_path);
            }
        }

        return $value;
    }

    public static function render_template($template, $vars = array())
    {
        if (!is_string($template)) {
            return '';
        }

        foreach ($vars as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }

        return $template;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @return string @output
     */
    public static function output($atts)
    {
        $show_banners = isset($atts['show_banners']) ? $atts['show_banners'] : false;
        $text_color = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white') . '!important';
        $btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white') . '!important';
        $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$fair_colors['Accent']) . '!important';
        $btn_border = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$fair_colors['Accent']) . '!important';

        $darker_btn_color = self::adjustBrightness($btn_color, -20);

        $logo_href = '';
        $logo_color = self::findBestLogo($atts["logo_color"]);
        $logo_color_array = explode('"', $logo_color);
        foreach ($logo_color_array as $href) {
            if (strpos(strtolower($href), '/doc/') !== false) {
                $logo_href = $href;
            }
        }

        if (empty($logo_color)) {
            $logo_color = '<img decoding="async" src="/doc/logo-color-en.webp" alt="Logo">';
            $logo_href = '/doc/logo-color-en.webp';
        }

        $pwe_groups_data = PWECommonFunctions::get_database_groups_data();
        $pwe_groups_contacts_data = PWECommonFunctions::get_database_groups_contacts_data();

        // Get domain address
        $current_domain = $_SERVER['HTTP_HOST'];

        foreach ($pwe_groups_data as $group) {
            if ($current_domain == $group->fair_domain) {
                $current_group = $group->fair_group;
                foreach ($pwe_groups_contacts_data as $group_contact) {
                    if ($group->fair_group == $group_contact->groups_name) {
                        if ($group_contact->groups_slug == "ob-marketing-media") {
                            $marketing_contact_data = json_decode($group_contact->groups_data);
                            $marketing_email = trim($marketing_contact_data->email);
                        }
                    }
                }
            }
        }

        $output = '';

        $promoteImage = self::findAllImages('/doc/galeria', 1);

        $template = self::multi_translation('promote_yourself_html');
        $download_label = self::multi_translation('download');
        $download_banners_label = self::multi_translation('download_banners');
        $download_logo_label = self::multi_translation('download_logo');
        $help_text = self::multi_translation('help_text');

        $output .=
            '<style>
                .pwe-image-container {
                    position: relative;
                    max-width: 45%;
                    float: right;
                }
                .download-hover {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background-color: rgba(0, 0, 0, 1);
                    color: white;
                    padding: 10px 20px;
                    border-radius: 5px;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                    cursor: pointer;
                }
                .download-uslug:hover .download-hover,
                .download-social:hover .download-hover {
                    opacity: 1;
                }
                .pwe-promote-text-block img{
                    max-width: 45%;
                    margin:18px;
                }
                .pwelement_' . self::$rnd_id . ' .pwe-btn {
                    color:' . $btn_text_color . ';
                    background-color: ' . $btn_color . ';
                    border: 1px solid ' . $btn_border . ';
                }
                .pwelement_' . self::$rnd_id . ' .pwe-btn:hover {
                    color: ' . $btn_text_color . ';
                    background-color: ' . $darker_btn_color . '!important;
                    border: 1px solid ' . $darker_btn_color . '!important;
                }
                .pwe-content-promote-item {
                    flex-wrap: wrap;
                    margin: 36px auto;
                    justify-content: space-around;
                }
                .pwe-border-element {
                    border: 2px solid ' . $btn_color . ';
                    border-radius: 10px;
                }
                .pwe-content-promote-item .btn-icon-right {
                    color:white !important;
                }
                .pwe-content-promote-item .pwe-content-promote-element {
                    margin: 18px;
                    flex:1;
                    justify-content: space-between;
                    align-items: center;
                    max-width: 250px;
                    display:flex !important;
                    gap: 5px;
                }
                .pwe-content-promote-item .pwe-content-promote-element h3 {
                    margin:0;
                    text-align: center;
                }
                .pwe-content-promote-item .pwe-content-promote-element img {
                    max-height: 150px;
                    object-fit: contain;
                }
                .pwe-content-promote-item div .btn {
                    transform: none !important;
                    white-space: unset !important;
                    font-size: 16px;
                }
                .pwe-content-promote-item div .btn-container {
                    display: flex;
                    justify-content: center;
                }
                .pwe-content-promote-item__help {
                    width: 80%;
                    max-width: 860px;
                    margin: auto;
                }
                .pwe-content-promote-item__help h2 {
                    margin-top: 0;
                    color:' . $text_color . ';
                }
                .pwe-content-promote-item__help div {
                    margin-top: 18px;
                }
                .pwe-content-promote-item__help a{
                    font-weight: 600;
                    color:' . $text_color . ';
                }
                .pwe-hide-promote {
                    width: 66%;
                    margin: 0 auto;
                }
                .pwe-content-promote-item__help :is(h2, a) {
                    font-size: 24px !important;
                }
                .pwe-content-promote-single-tile {
                    padding: 36px 0;
                }
                .pwe-content-promote-tile-container {
                    justify-content: space-between;
                    margin: 36px 0;
                    gap: 36px;
                }
                .pwe-content-promote-tile {
                    aspect-ratio: 1 / 1;
                    object-fit: cover;
                    max-width: 300px !important;
                    width: 100%;
                    height: 100%;
                    margin: auto;
                    border-radius: 24px;
                }
                .pwe-content-promote-tile-info {
                    max-width: 700px;
                    padding: 18px;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-around;
                    gap: 12px;
                }
                .pwe-content-promote-tile-info h5,
                .pwe-content-promote-tile-info ul,
                .pwe-content-promote-tile-info p {
                    margin: 0;
                }
                .pwe-content-promote-tile-btn {
                    max-width: 700px;
                    display: flex;
                    margin-left: auto;
                    justify-content: center;
                }
                @media(max-width:960px) {
                    .pwe-image-container,
                    .pwe-promote-text-block {
                        max-width:100% !important;
                    }
                    .pwe-image-container {
                        float: unset;
                        margin-top: 18px;
                    }
                    .pwe-promote-top-container {
                        display: flex;
                        flex-direction: column-reverse;
                    }
                    .download-hover {
                        opacity: 1;
                        top: 75%;
                        padding: 5px 5px;
                    }
                    .pwe-content-promote-item__help {
                        padding: 9px !important;
                        text-align: center;
                        width: 100%;
                    }
                    .pwe-hide-promote {
                        width: 100%;
                    }
                    .pwe-content-promote-tile-container {
                        flex-direction: column;
                        align-items: center;
                    }
                    .pwe-content-promote-tile-btn {
                        max-width: 100%;
                        margin-left: 0;
                    }
                    .pwe-content-promote-tile-info {
                        max-width: 100%;
                    }
                }
                @media (max-width:600px) {
                    .promote-img-contener {
                        order: 2;
                        text-align: center;
                    }
                    .pwe-promote-text-block img {
                        float: unset;
                        max-width: 90%;
                    }
                    .pwe-promote-text-block {
                        display: flex;
                        flex-direction: column;
                    }
                    .pwe-content-promote-item.pwe-flex {
                        flex-direction: column;
                        align-items: center;
                    }
                    .pwelement .h2.mobile-kons-email {
                        font-size: calc(7px + 3vw) !important;
                    }
                }
                .promote-element-background-element {
                    background: lightgrey;
                }
            </style>

            <div id="promoteYourself" >';

        $output .= self::render_template($template, array(
            'marketing_email' => $marketing_email,
        ));

        $output .= '
            <div class="pwe-flex pwe-content-promote-item pwe-border-element">';

        if ($show_banners != 'true') {
            $promoteBaners = self::findAllImages('/doc/wypromuj', 4);

            foreach ($promoteBaners as $baner) {
                switch (true) {
                    case (strpos($baner, '800_pl') != false):
                        $baner800pl = $baner;
                        break;
                    case (strpos($baner, '800_en') != false):
                        $baner800en = $baner;
                        break;
                    case (strpos($baner, '1200_pl') != false):
                        $baner1200pl = $baner;
                        break;
                    case (strpos($baner, '1200_en') != false):
                        $baner1200en = $baner;
                        break;
                }
            }

            $pwe_groups_data = PWECommonFunctions::get_database_groups_data();
            $current_domain = $_SERVER['HTTP_HOST'];

            if (!empty($pwe_groups_data)) {
                foreach ($pwe_groups_data as $group) {
                    if ($current_domain == $group->fair_domain) {
                        $fair_group = $group->fair_group;
                    }
                }
            }

            if (stripos(do_shortcode('[trade_fair_date]'), 'nowa data') == false) {
                $output .= '
                        <div class="pwe-column pwe-content-promote-element">
                            <h3>' . $download_banners_label . '</h3>
                            <p>800×800</p>
                            <span class="btn-container">
                                <a href="' . self::languageChecker(
                    <<<PL
                                        $baner800pl
                                    PL,
                    <<<EN
                                        $baner800en
                                    EN
                )
                    . ' " class="pwe-link btn pwe-btn" target="_blank" rel="nofollow" title="800x800" >' . $download_label . '<i class="fa fa-inbox2"></i></a>
                            </span>
                            <p>1200x200</p>
                            <span class="btn-container">
                                <a href="' . self::languageChecker(
                        <<<PL
                                        $baner1200pl
                                    PL,
                        <<<EN
                                        $baner1200en
                                    EN
                    )
                    . ' " class="pwe-link btn pwe-btn" target="_blank" rel="nofollow" title="1200x200" >' . $download_label . '<i class="fa fa-inbox2"></i></a>
                            </span>
                        </div>';
            }
        }

        $output .= '
                    <div class="pwe-column pwe-content-promote-element">
                        <h3>' . $download_logo_label . '</h3>
                        ' . $logo_color . '
                        <span class="btn-container">
                            <a href="' . $logo_href . '" class="pwe-link btn pwe-btn" target="_blank" rel="nofollow" title="[trade_fair_name] logo" >' . $download_label . '<i class="fa fa-inbox2"></i>
                            </a>
                        </span>
                    </div>
                        <div class="pwe-column pwe-content-promote-element">
                            <h3>' . $download_logo_label . '</h3>
                            <img src="/wp-content/plugins/pwe-media/media/logo_pwe_black.webp" alt="PWE-logo"/>
                            <div>
                                <span class="btn-container">
                                    <a href="https://warsawexpo.eu/docs/Logo_PWE.zip" class="pwe-link btn pwe-btn" target="_blank" rel="nofollow" title="PWE-logo">' . $download_label . '<i class="fa fa-inbox2"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="pwe-border-element style-accent-bg pwe-content-promote-item__help pwe-block-padding">
                        <h2>' . $help_text . '</h2>
                        <div class="text-centered link-text-underline">';
        if ($fair_group === "gr3") {
            $output .= '<a class="h2 mobile-kons-email" href="mailto:media3@warsawexpo.eu"><span style="display:inline-block;">media3</span><span style="display:inline-block;">@warsawexpo.eu</span></a>';
        } else {
            $output .= '<a class="h2 mobile-kons-email" href="mailto:konsultantmarketingowy@warsawexpo.eu"><span style="display:inline-block;">konsultantmarketingowy</span><span style="display:inline-block;">@warsawexpo.eu</span></a>';
        }
        $output .= '
                        </div>
                    </div>
                </div>';

        return $output;
    }
}
