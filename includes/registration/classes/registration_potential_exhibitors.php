<?php

/**
 * Class PWERegistrationPotentialExhibitors
 * Extends PWERegistration class and defines a custom Visual Composer element.
 */
class PWERegistrationPotentialExhibitors extends PWERegistration {

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
     * @param array @atts pwe-registration-fairs-options
     */
    public static function output($atts, $registration_type, $registration_form_id) {
        
        $fairs_json = PWECommonFunctions::json_fairs();

        // CSS <----------------------------------------------------------------------------------------------<
        require_once plugin_dir_path(dirname( __FILE__ )) . 'assets/style.php';

        $output .= '
        <div id="pweRegistration" class="pwe-registration potential-exhibitors">
            <div class="pwe-registration-column">
                <div id="pweFormContent" class="pwe-form-content">
                    <div class="pwe-registration-title main-heading-text">
                        <h4 class="custom-uppercase"><span>Generator zaproszeń<br>(Potencjalny wystawca)</span></h4>
                    </div>
                </div>
                <div class="pwe-registration-form">
                    <div class="pwe-registration-fairs-select-container">
                        <div class="pwe-registration-fairs-select" id="fairSelect">
                            <div class="pwe-registration-fairs-select-box">
                                <span class="pwe-registration-fairs-selected-text">Wybierz Targi</span>
                                <i class="arrow-down">&#9660;</i>
                            </div>
                            <input type="text" id="searchInput" class="pwe-registration-fairs-search-input" placeholder="Wyszukaj..." />
                            <div class="pwe-registration-fairs-options-container">
                                <div class="pwe-registration-fairs-option" domain="">Wybierz Targi</div>';
                                foreach ($fairs_json as $fair) {
                                    if (!empty($fair["name_pl"])) {
                                        $output .= '
                                        <div 
                                            class="pwe-registration-fairs-option" 
                                            name="' . $fair["name_pl"] . '" 
                                            domain="' . $fair["domain"] . '" 
                                            date-start="' . $fair["date_start"] . '" 
                                            date-end="' . $fair["date_end"] . '">
                                            ' . $fair["name_pl"] . '
                                        </div>';
                                    }
                                }
                                $output .= '
                            </div>
                        </div>
                        <div class="pwe-registration-fairs-radio-buttons">
                            <label><input type="radio" name="language" lang="pl" checked> PL</label>
                            <label><input type="radio" name="language" lang="en"> EN</label>
                        </div>
                    </div>
                    [gravityform id="'. $registration_form_id .'" title="false" description="false" ajax="false"]
                </div>
            </div>
        </div>';

        return $output;
    }
}
