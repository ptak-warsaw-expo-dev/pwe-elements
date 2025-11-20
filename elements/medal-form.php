<?php
/**
* Class PWElementMedalForm
* Extends PWElements class and defines a pwe Visual Composer element.
*/
class PWElementMedalForm extends PWElements {

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
                'type' => 'dropdown',
                'group' => 'PWE Element',
                'heading' => __('Select Form', 'pwe_element'),
                'param_name' => 'medale_form_id',
                'save_always' => true,
                'value' => array_merge(
                  array('Wybierz' => ''),
                  self::$fair_forms,
                ),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementMedalForm',
                ),
            ),
        );
        return $element_output;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
    * Returns the HTML output as a string.
    *
    * @return string @output
    */
    public static function output($atts) {
        extract( shortcode_atts( array(
            'medale_form_id' => '',
        ), $atts ));


        $text_color = 'color:' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'black') . '!important;';


        $output .= '
          <style>
            .fair-medal {
              max-width:800px;
              margin: 0 auto;
            }
            .fair-medal .fair-medal__header img, .fair-medal .fair-medal__footer img, .fair-medal .fair-medal-content, .fair-medal .fair-medal-form .gfield, .fair-medal .fair-rules, .fair-medal .gform_confirmation_message  {
              border:1px solid #dadce0;
              border-radius: 15px;
              -webkit-box-shadow: 8px 8px 24px -5px rgba(218, 220, 224, 1);
              -moz-box-shadow: 8px 8px 24px -5px rgba(218, 220, 224, 1);
              box-shadow: 8px 8px 24px -5px rgba(218, 220, 224, 1);
              margin:5px auto;
              width: 100%;
            }
            .fair-medal .fair-medal-content {
              margin-bottom: 20px;
            }
            .fair-medal .gfield_checkbox label {
              cursor: pointer;
            }
            .fair-medal .fair-medal-content__color-box {
              background-color:red;
              height:15px;
              border-radius: 15px 15px 0 0;
            }
            .fair-medal .fair-medal-content_container {
              padding: 0 5% 5%;
            }
            .fair-medal .fair-medal-form .gfield, .fair-medal .gform_confirmation_message {
              padding: 3% 5%;
            }
            .fair-medal .fair-medal-form .gfield label {
                font-weight: 700 !important;
                font-size: 17px !important
            }
            .fair-medal .fair-medal-form .gfield .ginput_container{
              border:0px;
            }
            .fair-medal .fair-medal-form .ginput_container_email input, .fair-medal .fair-medal-form .ginput_container_text input, .fair-medal .fair-medal-form .ginput_container_phone input {
              border: 0px !important;
              box-shadow: none !important;
              border-bottom: 1px solid #dadce0 !important;
              border-radius: 0px !important;
            }
            .fair-medal .fair-medal-form fieldset {
                padding: 8% 5% 5% !important;
                position: relative;
            }
            .fair-medal .fair-medal-form fieldset legend {
              position: absolute;
              top: 7%;
              font-weight:700 !important;
              font-size: 17px !important;
            }
            .fair-medal .fair-medal-form  .ginput_container_checkbox label {
              font-weight:400 !important;
              font-size: 14px !important;
            }
            .fair-medal .fair-medal-form  .custom-upload-label {
              margin: 5px auto !important;
              padding: 8px 16px !important;
              border-radius: 6px !important;
              cursor: pointer !important;
              font-weight: 500 !important;
              display: inline-block !important;
              border: 1px solid #dadce0 !important;
              font-size: 14px !important;
            }
            .fair-medal .fair-medal-form .zgoda {
              min-height:40px !important;
            }
            .fair-medal .fair-medal-form .zgoda legend {
              top: 15% !important;
            }
            .fair-medal .fair-medal-form .zgoda .ginput_container  {
              margin-top: 20px !important;
            }
           .fair-medal .gform_footer  input[type="submit"] {
              margin-top: 15px !important;
              border-radius: 13px !important;
            }
            .fair-medal .fair-rules {
              padding: 3% 5%;
              font-weight: 700;
            }
            .fair-medal .fair-rules a {
              margin-top: 5px;
              display: inline-block;
              font-weight: 500;
              text-decoration: underline;
              color: blue;
            }
            .fair-medal .gform_confirmation_message {
              margin-bottom: 15px;
            }
            .ginput_container_fileupload .gfield_validation_message {
              display:none !important;
            }
            .fair-medal .custom-upload-filename {
              font-size:12px;
              margin-left: 12px;
            }
            @media(max-width:900px){
              .fair-medal .fair-medal-form fieldset {
                padding-top: 80px !important;
              }
              .fair-medal .fair-medal-form fieldset legend {
                padding-right: 5%;
              }
            }
            @media(max-width:900px){
              .fair-medal .fair-medal-form fieldset {
                padding-top: 100px !important;
              }
            }
          </style>

          <div class="fair-medal">
            <div class="fair-medal__header">
              <img src="/wp-content/plugins/pwe-media/media/ptak-medale.webp" alt="fair-medal header" class="fair-medal__header-img" />
            </div>
            <div class="fair-medal-content">
              <div class="fair-medal-content__color-box"></div>
              <div class="fair-medal-content_container">
                <h1 class="fair-medal-content__title">
                  '.self::languageChecker('Zgłoszenie do Konkursu Medalowego - Ptak Warsaw Expo!', 'Application for the Medal Competition - Ptak Warsaw Expo!').'
                </h1>

                <p class="fair-medal-content__description">
                  '.self::languageChecker('Zgłoś swój udział w konkursie medalowym organizowanym podczas targów w <strong>Ptak Warsaw Expo!</strong>
                  Wyróżniamy innowacyjne, premierowe i wartościowe produkty oraz usługi prezentowane przez wystawców.
                  Wypełnij formularz i zgłoś swój produkt do jednej z kategorii konkursowych!', 'Submit your entry to the medal competition organized during the trade fair at <strong>Ptak Warsaw Expo!</strong> We recognize innovative, premiere, and valuable products and services presented by exhibitors. Fill out the form and enter your product in one of the competition categories!').'
                </p>
              </div>

            </div>
            <div class="fair-medal-form">
              [gravityform id="'. $medale_form_id .'" title="false" description="false" ajax="false"]
            </div>
            <div class="fair-rules">
             '.self::languageChecker('Regulamin', 'Terms and Conditions:').' :<br/>
              <a href="'.self::languageChecker('https://warsawexpo.eu/docs/Regulamin-Konkursu-Medalowego-Ptak-Warsaw-Expo.pdf', 'https://warsawexpo.eu/docs/Rules-of-the-Medal-Competition-Ptak-Warsaw-Expo.pdf').'"  target=_blank>'.self::languageChecker('Kliknij tutaj, aby przeczytać regulamin', 'Click here to read the terms and conditions:').'</a>
            </div>
            <div class="fair-medal__footer">
              <img src="/wp-content/plugins/pwe-media/media/medal.webp" alt="fair-medal footer" class="fair-medal__footer-img" />
            </div>
          </div>

          <script>
            document.addEventListener("DOMContentLoaded", function() {
              const fileInputs = document.querySelectorAll(".ginput_container_fileupload input[type=\'file\']");

              const allowedExtensions = ["jpg", "jpeg", "png", "gif", "pdf", "webp"];
              const maxFileSize = 1048576; // 1 MB

              fileInputs.forEach(function(fileInput) {
                fileInput.style.display = "none";


                const label = document.createElement("label");
                label.setAttribute("for", fileInput.id);
                label.classList.add("custom-upload-label");
                label.innerHTML = "📎 '.self::languageChecker('Dodaj plik', 'Add file').'";

                const fileNameSpan = document.createElement("span");
                fileNameSpan.classList.add("custom-upload-filename");
                fileNameSpan.textContent = "'.self::languageChecker('Brak wybranego pliku', 'No file selected').'";

                fileInput.parentNode.insertBefore(label, fileInput);
                fileInput.parentNode.insertBefore(fileNameSpan, fileInput.nextSibling);

                fileInput.addEventListener("change", function(event) {
                  const file = event.target.files[0];
                  if (!file) {
                    fileNameSpan.textContent = "'.self::languageChecker('Brak wybranego pliku', 'No file selected').'";
                    fileNameSpan.classList.remove("error");
                    return;
                  }

                  const fileName = file.name;
                  const fileExtension = fileName.split(".").pop().toLowerCase();
                  const fileSize = file.size;


                  if (!allowedExtensions.includes(fileExtension)) {
                    fileNameSpan.textContent = "❌ '.self::languageChecker('Niedozwolony format pliku', 'Invalid file format').' (" + fileExtension + ")";
                    fileNameSpan.classList.add("error");
                    fileInput.value = "";
                    return;
                  }

                  // walidacja rozmiaru
                  if (fileSize > maxFileSize) {
                    fileNameSpan.textContent = "❌ '.self::languageChecker('Plik jest zbyt duży', 'The file is too large').' (maks. 1 MB)";
                    fileNameSpan.classList.add("error");
                    fileInput.value = "";
                    return;
                  }

                  fileNameSpan.textContent = fileName;
                  fileNameSpan.classList.remove("error");
                });
              });
            });
          </script>

        ';

    return $output;
    }
}
