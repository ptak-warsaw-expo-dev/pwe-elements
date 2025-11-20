<?php

/**
 * Class PWElementWhyItsWorth
 * Extends PWElements class and defines a custom Visual Composer element for vouchers.
 */
class PWElementWhyItsWorth extends PWElements {

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
            'type' => 'textfield',
            'group' => 'PWE Element',
            'heading' => __('Title custom', 'pwelement'),
            'param_name' => 'whyitsworth_items_heading',
            'save_always' => true,
            'dependency' => array(
              'element' => 'pwe_element',
              'value' => 'PWElementWhyItsWorth',
            ),
          ),
          array(
            'type' => 'param_group',
            'group' => 'PWE Element',
            'heading' => __('Items icons', 'pwelement'),
            'param_name' => 'whyitsworth_items',
            'save_always' => true,
            'dependency' => array(
              'element' => 'pwe_element',
              'value' => 'PWElementWhyItsWorth',
            ),
            'params' => array(
              array(
                'type' => 'attach_image',
                'heading' => __('Icon', 'pwelement'),
                'param_name' => 'whyitsworth_item_icon',
                'save_always' => true,
              ),
              array(
                'type' => 'textfield',
                'heading' => __('Title', 'pwelement'),
                'param_name' => 'whyitsworth_item_title',
                'save_always' => true,
                'admin_label' => true,
              ),
              array(
                'type' => 'textfield',
                'heading' => __('Back text', 'pwelement'),
                'param_name' => 'whyitsworth_item_backtext',
                'save_always' => true,
                'admin_label' => true,
              ),
            ),
          ),
      );
        return $element_output;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public static function output($atts) {
      extract( shortcode_atts( array(
            'whyitsworth_items' => '',
            'whyitsworth_items_heading' => '',
        ), $atts ));

        $whyitsworth_items_urldecode = urldecode($whyitsworth_items);
        $whyitsworth_items_json = json_decode($whyitsworth_items_urldecode, true);
        $heading_text = self::languageChecker(
          <<<PL
            Dlaczego warto <strong>zostać wystawcą?</strong>
          PL,
          <<<EN
            Why should you <strong>become an exhibitor?</strong>
          EN
        );
        $whyitsworth_items_heading = !empty($whyitsworth_items_heading) ? $whyitsworth_items_heading : $heading_text;

        $output .= '
        <style>
          .why-worthy-'. self::$rnd_id .' h2 {
            font-weight: 300;
            text-transform: uppercase;
            text-align: center;
            border-bottom: 3px solid #09adbc;
            max-width: 750px;
            margin: 50px auto 35px;
            padding-bottom: 10px;
          }

          .why-worthy-'. self::$rnd_id .' .why-worthy_container {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
          }

          .why-worthy-'. self::$rnd_id .' .why-worthy_element {
            position: relative;
            width: 200px;
            height: 200px;
            perspective: 1000px;
            box-shadow: 0px 0px 12px #cccccc;
            border-radius: 18px;
          }

          .why-worthy-'. self::$rnd_id .' .why-worthy_element_visible,
          .why-worthy-'. self::$rnd_id .' .why-worthy_element_hidden {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            transition: transform 0.6s;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            flex-direction: column;
          }

          .why-worthy-'. self::$rnd_id .' .why-worthy_element_visible img {
            max-width: 60%;
            margin: 0 auto;
          }

          .why-worthy-'. self::$rnd_id .' .why-worthy_element_hidden {
            transform: rotateX(180deg);
          }

          .why-worthy-'. self::$rnd_id .' .why-worthy_element_hidden p,
          .why-worthy-'. self::$rnd_id .' .why-worthy_element_visible p {
            line-height: 1.5;
            margin: 0 !important;
            font-size: 15px;
          }
          .why-worthy-'. self::$rnd_id .' .why-worthy_element_visible p {
            text-transform:uppercase;
          }
          .why-worthy-'. self::$rnd_id .' .why-worthy_element:hover .why-worthy_element_visible {
            transform: rotateX(180deg);
          }

          .why-worthy-'. self::$rnd_id .' .why-worthy_element:hover .why-worthy_element_hidden {
            transform: rotateX(360deg);
          }

          @media(max-width:620px) {
            .why-worthy-'. self::$rnd_id .' .why-worthy_element {
              width: 48%;
            }

            .why-worthy-'. self::$rnd_id .' .why-worthy_element_hidden p,
            .why-worthy-'. self::$rnd_id .' .why-worthy_element_visible p {
              line-height: 1.2;
              margin: 0 !important;
              font-size: 14px;
              padding: 5px;
            }
          }
        </style>
        <div class="why-worthy-'. self::$rnd_id .'">
          <h2>' . $whyitsworth_items_heading .'</h2>
          <div class="why-worthy_container">
        ';
        if (is_array($whyitsworth_items_json)) {
          foreach ($whyitsworth_items_json as $whyitsworth_item) {
            $whyitsworth_item_icon_nmb = $whyitsworth_item["whyitsworth_item_icon"];
            $whyitsworth_item_icon_src = wp_get_attachment_url($whyitsworth_item_icon_nmb);

            $whyitsworth_item_title = $whyitsworth_item["whyitsworth_item_title"];
            $whyitsworth_item_backtext = $whyitsworth_item["whyitsworth_item_backtext"];

            $output .= '
              <div class="why-worthy_element">
                <div class="why-worthy_element_visible">
                  <img src="'. $whyitsworth_item_icon_src .'" />
                  <p>'. $whyitsworth_item_title .'</p>
                </div>
                <div class="why-worthy_element_hidden">
                  <p>'. $whyitsworth_item_backtext .'</p>
                </div>
              </div>';
          }
        }
        $output .= '
          </div></div>';

        return $output;

    }
}