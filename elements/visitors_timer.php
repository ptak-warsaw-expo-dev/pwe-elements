<?php 

/**
 * Class PWElementVisitorsTimer
 * Extends PWElements class and defines a custom Visual Composer element for vouchers.
 */
class PWElementVisitorsTimer extends PWElements {

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

        $element_output = array();
        return $element_output;
    }

    /**
     * Static method to check all forms registration count.
     * Returns number of registrations.
     * 
     * @param array forms ids
     */
    public static function findAllBeneficients($forms_id = '') {
        $element_output = 0;
        if($forms_id != ''){
        } else {
            $all_forms = GFAPI::get_forms();
            foreach($all_forms as $form){
                if(strpos(strtolower($form['title']), 'rejestracja') !== false ){
                    $form_id[] = $form['id'];
                }
            }
        }

        foreach($form_id as $key){
            $form = GFAPI::get_entries($key);
            $form_count = count($form);
            $element_output +=  $form_count;
        }

        if($element_output < 3000){
            $element_output += (1000 - $element_output/3);
        }
        
        return $element_output;
    }

    /**
     * Static method to generate sum of target forms registrations.
     * Returns the HTML output as a string.
     * 
     * @param array forms ids
     */
    public static function output($forms_id = '') {

        $output = self::findAllBeneficients($form_id);

        return $output;
    }
}