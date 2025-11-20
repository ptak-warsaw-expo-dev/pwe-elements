<?php

/**
 * Class PWERegistrationAccreditations
 * Extends PWERegistration class and defines a custom Visual Composer element.
 */
class PWERegistrationAccreditations extends PWERegistration {

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
    public static function output($atts, $registration_type,  $registration_form_id) {
        
        $fairs_json = PWECommonFunctions::json_fairs(); 

        // CSS <----------------------------------------------------------------------------------------------<
        require_once plugin_dir_path(dirname( __FILE__ )) . 'assets/style.php';
        
        $output .= '
        <div id="pweRegistration" class="pwe-registration accreditations">
            <div class="pwe-registration-column">
                <div id="pweFormContent" class="pwe-form-content">
                    <div class="pwe-registration-title">
                        <h3>'. self::languageChecker('Formularz akredytacyjny', 'Accreditation form') .'</h3>
                    </div>
                    <div style="display: none;">
                        <p>Rejestracja upoważnia do odebrania identyfikatora prasowego pod warunkiem okazania aktualnej legitymacji prasowej w recepcji dla zwiedzających podczas trwania targów.</p>
                    </div>
                </div>
                <div class="pwe-registration-form">
                    <div class="pwe-registration-fairs-select-container">
                        <div class="pwe-registration-fairs-select" id="fairSelect">
                            <div class="pwe-registration-fairs-select-box">
                                <span class="pwe-registration-fairs-selected-text">'. self::languageChecker('Wybierz Targi', 'Select Fair') .'</span>
                                <i class="arrow-down">&#9660;</i>
                            </div>
                            <input type="text" id="searchInput" class="pwe-registration-fairs-search-input" placeholder="Wyszukaj..." />
                            <div class="pwe-registration-fairs-options-container">
                                <div class="pwe-registration-fairs-option" domain="">'. self::languageChecker('Wybierz Targi', 'Select Fair') .'</div>';
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
                    </div>
                    [gravityform id="'. $registration_form_id .'" title="false" description="false" ajax="false"]
                </div>
            </div>
        </div>';

        return $output;
    }
}