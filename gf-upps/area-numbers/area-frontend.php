<?php

class GFAreaNumbersFrontend {

	function __construct() {
        add_action( 'gform_enqueue_scripts', array($this, 'area_enqueue_scripts'), 10, 2 );
	}

	function area_enqueue_scripts($form, $is_ajax) {

		$form_id = $form['id'];
		$field_arr = [];
        
		foreach($form['fields'] as $field) {
            if (property_exists($field, 'smartPhoneFieldGField') && $field->smartPhoneFieldGField) {

                // $user_ip = $_SERVER["REMOTE_ADDR"];

                // $json       = file_get_contents("http://ipinfo.io/{$user_ip}");
                // $details    = json_decode($json);
                
                // var_dump($details);


                // $url = "https://ipinfo.io/212.244.73.146";
                // $ch = curl_init();
                // curl_setopt($ch, CURLOPT_URL, $url);
                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                // $response = curl_exec($ch);
                // if (curl_errno($ch)) {
                //     die('cURL error: ' . curl_error($ch));
                // }
                // curl_close($ch);

                // var_dump($response);

                $field["enableAutocomplete"] = true;
                $field["autocompleteAttribute"] = 'tel';
                $field['displayOnly'] = true;

                $fieldId = "input_{$field->formId}";

                add_filter( 'gform_field_content_' . $field->formId . '_' . $field->id , array($this, 'field_disabled'), 10, 5 );

                $field_arr[$fieldId] = [];
                
                $field_arr[$fieldId][] = "#{$fieldId}_{$field->id}";
                $field_arr[$fieldId][] = strtolower($field['defaultCountryGField']);
            }
		}

		if (count($field_arr) === 0) { 
            return; 
        }

        $css_file1 = plugins_url('css/intlTelInput.min.css', __FILE__);		
        $css_version1 = filemtime(plugin_dir_path(__FILE__) . 'css/intlTelInput.min.css');    
		wp_enqueue_style( 'spf_intlTelInput', $css_file1, array(), $css_version1 );

        $css_file2 = plugins_url('css/spf_style.css', __FILE__);		
        $css_version2 = filemtime(plugin_dir_path(__FILE__) . 'css/spf_style.css');    
		wp_enqueue_style( 'spf_style', $css_file2, array('spf_intlTelInput'), $css_version2 );

        $js_file1 = plugins_url('js/intlTelInput-jquery.min.js', __FILE__);		
        $js_version1 = filemtime(plugin_dir_path(__FILE__) . 'js/intlTelInput-jquery.min.js');        
        wp_enqueue_script('area_intlTelInput', $js_file1, array( 'jquery' ), $js_version1, true);

        $js_file3 = plugins_url('js/area-numbers.js', __FILE__);		
        $js_version3 = filemtime(plugin_dir_path(__FILE__) . 'js/area-numbers.js');        
        wp_enqueue_script('area-numbers_main', $js_file3, array( 'area_intlTelInput' ), $js_version3, true);

        wp_localize_script('area-numbers_main', 'area_data', array(
            'utilsScript' => plugin_dir_path(__FILE__) .'frontend/js/utils.js',
            'elements' =>  $field_arr
        ));

        echo '<script src="https://unpkg.com/imask"></script>';
	}

    function field_disabled($content){
        return str_replace('<input', '<input disabled', $content);
    }
}

new GFAreaNumbersFrontend();