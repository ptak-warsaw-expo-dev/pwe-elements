<?php

/**
 * Class PWElementPopup
 * Extends PWElements class and defines a pwe Visual Composer element.
 */
class PWElementPopup extends PWElements {

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
                'heading' => __('Modal title', 'pwe_element'),
                'param_name' => 'popup_modal_title',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementPopup',
                ),
            ),
            array(
                'type' => 'textarea_raw_html',
                'group' => 'PWE Element',
                'heading' => __('Modal content', 'pwe_element'),
                'param_name' => 'popup_modal_content',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementPopup',
                ),
            ),
            array(
                'type' => 'textarea',
                'group' => 'PWE Element',
                'heading' => __('Custom modal style', 'pwe_element'),
                'param_name' => 'popup_modal_style',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementPopup',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Modal time to open', 'pwe_element'),
                'param_name' => 'popup_modal_time_to_open',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementPopup',
                ),
            ),
        );

        return $element_output;
    }

    public static function output($atts) {

        extract( shortcode_atts( array(
            'popup_modal_title' => '',
            'popup_modal_content' => '',
            'popup_modal_style' => '',
            'popup_modal_time_to_open' => '25000',
        ), $atts ));


        $popup_modal_content = $is_empty ? $popup_modal_content : PWECommonFunctions::decode_clean_content($popup_modal_content);

        $output = '';

        $output .= '
            <style>
                .main-container .row-container .row-parent:has(.custom-popup-modal__container) {
                    padding: 0 !important;
                }
                .custom-popup-modal__container {
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    z-index: 100;
                    width: 100%;
                    height: 100%;
                    margin: auto;
                    display: none;
                }
                .custom-popup-modal__container:before {
                    content: "";
                    position: absolute;
                    width: 100vw;
                    height: 100vh;
                    background: black;
                    z-index: -1;
                    opacity: 0.6;
                    top: 0;
                    left: 0;
                }
                .custom-popup-modal__content {
                    max-width: 800px;
                    width: 100%;
                    margin: auto;
                    display: flex;
                    flex-direction: column;
                    justify-content: flex-start;
                    align-items: center;
                    background-color: #f5f5f5;
                    padding: 36px;
                    border-radius: 18px;
                    z-index: 2;
                }
                .custom-popup-modal__title {
                    position: relative;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    width: 100%;
                }
                .custom-popup-modal__title h2 {
                    text-transform: uppercase;
                    color: black;
                    font-weight: 700;
                    font-size: 26px;
                    margin: 12px;
                }
                .custom-popup-modal__title .custom-popup-modal__close {
                    position: absolute;
                    right: 0;
                    padding: 6px;
                    margin: 6px;
                    font-size: 24px;
                    cursor: pointer;
                    filter: brightness(0);
                }
                .custom-popup-modal__desc {
                    padding: 18px;
                    display: flex;
                    flex-direction: column;
                    gap: 18px;
                }
                .custom-popup-modal__desc p{
                    margin: 0;
                }

                .custom-popup-modal__desc a {
                    color: white !important;
                    background: black;
                    padding: 12px 30px;
                    border-radius: 36px;
                    font-size: 14px;
                    display: block;
                    margin: 0 auto;
                    max-width: 240px;
                    text-align: center;
                }

                ' . $popup_modal_style . '
            </style>
        ';

        $output .= '
            <div class="custom-popup-modal__container">
                <div class="custom-popup-modal__content">
                    <div class="custom-popup-modal__title">
                        <h2> ' . $popup_modal_title . '</h2>
                        <span class="custom-popup-modal__close">❌</span>
                    </div>
                    <div class="custom-popup-modal__desc">
                        ' . $popup_modal_content . '
                    </div>
                </div>
            </div>
        ';

        $output .= '
        <script>
            // Początkowo ukryj popup
            document.addEventListener("DOMContentLoaded", function () {
                const popup = document.querySelector(".custom-popup-modal__container");
                const closeBtn = document.querySelector(".custom-popup-modal__close");

                function showPopup() {
                popup.style.display = "flex";
                }

                // Zamknij popup po kliknięciu X
                closeBtn.addEventListener("click", function () {
                popup.style.display = "none";
                });

                setTimeout(showPopup, ' . $popup_modal_time_to_open . ');
            });
        </script>';

        return $output;
    }
}