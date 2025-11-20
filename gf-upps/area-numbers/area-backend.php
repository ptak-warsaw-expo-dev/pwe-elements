<?php

class GFAreaNumbersBackend {

    function __construct() {
        add_action( 'gform_field_standard_settings', array($this,'gf_area_number_settings'), 10, 2 );
		add_action( 'gform_editor_js', array($this,'gf_area_number_script') );
		add_filter( 'gform_tooltips', array($this,'gf_area_number_tooltips') );
		//add_action( 'admin_enqueue_scripts', array( $this, 'gf_area_number_admin_scripts' ) );
    }

	function gf_area_number_settings ( $position, $form_id ) {
        if ( $position == 25 ) { ?>
			<li class="area_default_setting field_setting">
				<label for="field_admin_label" class="section_label">
					<?php _e("Default country", "gravityforms"); ?>
					<?php gform_tooltip("area_default_tooltips"); ?>
				</label>
				<ul>
					<li>
						<input type="checkbox" id="spf_enable_value" onclick="SetFieldProperty('smartPhoneFieldGField', this.checked);" checked/>
						<label for="spf_enable_value" class="inline"><?php _e("Enable", "gravityforms"); ?></label>
					</li>
				</ul>
				<select name="area_default_country_value" id="area_default_country_value" onChange="SetFieldProperty('defaultCountryGField', this.value);">
					<?php
					foreach (GFAreaCodes::get_countries() as $value => $name) {
						echo '<option value="' . $value . '">' . $name . '</option>';
					}
					?>
				</select>

			</li>
            <?php
        }
    }

    function gf_area_number_script() {
		?>
	    <script type='text/javascript'>
	        fieldSettings.phone += ", .area_default_setting";
			
	       	jQuery(document).bind("gform_load_field_settings", function(event, field, form){
                jQuery("#area_default_country_value").val( field["defaultCountryGField"] );
			});            
	    </script>
	    <?php
    }

    function gf_area_number_tooltips() {
        $tooltips['area_default_tooltips'] = esc_html__("Select one for showing specific country. Default: PL", "gravityforms");
		return $tooltips;
	}
	
	// function gf_area_number_admin_scripts() {
	// 	$js_file = plugins_url('js/area_admin.js', __FILE__);		
    //     $js_version = filemtime(plugin_dir_path(__FILE__) . 'js/area_admin.js');
    //     wp_enqueue_script('area_admin-js', $js_file, array(), $js_version, true);
	// }
}

new GFAreaNumbersBackend();