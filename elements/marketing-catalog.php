<?php
/**
* Class PWElementContact
* Extends PWElements class and defines a pwe Visual Composer element.
*/
class PWElementMarketingCatalog extends PWElements {

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
        // $element_output = array(
        //     array(
        //         'type' => 'textfield',
        //         'group' => 'PWE Element',
        //         'heading' => __('Category', 'pwelement'),
        //         'param_name' => 'posts_category',
        //         'save_always' => true,
        //         'dependency' => array(
        //           'element' => 'pwe_element',
        //           'value' => 'PWElementPosts',
        //         ),
        //     ),
        // );
        // return $element_output;
    }
    
    /**
     * Static method to generate the HTML output for the PWE Element.
    * Returns the HTML output as a string.
    * 
    * @return string @output 
    */
    public static function output($atts) {

        $output .= '
        
        ';         

    return $output;
    }
}
