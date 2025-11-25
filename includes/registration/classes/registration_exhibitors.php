<?php

/**
 * Class PWERegistrationExhibitors
 * Extends PWEProfile class and defines a custom Visual Composer element.
 */
class PWERegistrationExhibitors extends PWERegistration {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public static function output($atts, $registration_type, $registration_form_id) {
        $btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white');
        $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$main2_color);

        $darker_btn_color = self::adjustBrightness($btn_color, -20);

        $pwe_groups_data = PWECommonFunctions::get_database_groups_data();
        $current_domain = $_SERVER['HTTP_HOST'];
        $current_fair_group = null;

        foreach ($pwe_groups_data as $item) {
            if ($item->fair_domain === $current_domain) {
                $current_fair_group = $item->fair_group;
                break;
            }
        }

        $domain_gr_exhib = $current_fair_group;

        if($current_fair_group === "gr3"){
            $registration_text = PWERegistrationVisitors::multi_translation("exhibitor_registration_text");
            $registration_title = PWERegistrationVisitors::multi_translation("exhibitor_ask_for_stand");
        } else {
            $registration_text = PWERegistrationVisitors::multi_translation("exhibitor_registration_text_gr12");
            $registration_title = PWERegistrationVisitors::multi_translation("exhibitor_registration_for");
        }


        $output = '
        <style>
            @media (min-width: 959px) {
                .wpb_column:has(#top10),
                .wpb_column:has(#pweRegistration) {
                    display: table-cell !important;
                }
            }
            .wpb_column #pweRegistration {
                margin: 0 auto;
            }
            #pweRegistration {
                max-width: 555px !important;
            }
        </style>';

        // CSS <----------------------------------------------------------------------------------------------<
        require_once plugin_dir_path(dirname( __FILE__ )) . 'assets/style.php';

        $output .= '
        <div id="pweRegistration" class="pwe-registration for-exhibitors">
            <div class="pwe-registration-column">
                <div id="pweFormContent" class="pwe-form-content">
                    <div id="main-content" class="pwe-registration-title main-heading-text">
                        <h1 class="custom-uppercase" style="font-size: 26px;"><span>'. $registration_title .'</span></h1>
                    </div>
                    <div class="pwe-registration-text">
                        <p>'.  $registration_text .'</p>
                    </div>
                </div>
                <div class="pwe-registration-form">
                    [gravityform id="'. $registration_form_id .'" title="false" description="false" ajax="false"]
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {

                const labels = document.querySelectorAll("label.gfield_label");

                labels.forEach(label => {
                    if (label.textContent.trim().toLowerCase() === "patron") {

                        const input = document.getElementById(label.getAttribute("for"));
                        if (input) {
                            input.value = "'. $domain_gr_exhib .'";
                        }
                    }
                });
            });
        </script>';

        return $output;
    }
}