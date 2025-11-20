<?php
/**
* Class PWActiveQR
* Extends PWElements class and defines a pwe Visual Composer element.
*/
class PWEActiveQR extends PWElements {

    /**
     * Constructor method.
    * Calls parent constructor and adds an action for initializing the Visual Composer map.
    */
    public function __construct() {
        parent::__construct();
    }
    
    /**
    * Getting data for form.
    * Returns an Array
    *
    * @return array @output
    */
    private static function findData($qr_code) {
        $data = array();
        $result = explode('rnd', $qr_code)[0];
        $result = preg_replace('/[a-zA-Z]/', '', $result);
        $entry_id = substr($result, 3);
        $form = '';
        $form_pot = '';
        $entry_pot = '';


        $all_forms = GFAPI::get_forms();
        $entry = GFAPI::get_entry($entry_id);
        // echo '<script>console.log("'.get_local().'")</script>';
        
        foreach($all_forms as $single_form){
            if($single_form['id'] == $entry['form_id']){
                $form = $single_form;
            } else if($single_form['title'] == 'Potwierdzenie rejestracji odwiedzajacego - krok 2'){
                $form_pot = $single_form;
            }
        }
        
        $email_id = '';
        $phone_id = '';

        foreach($form['fields'] as $key){            
            if(strpos(strtolower($key['label']), 'email') !== false ){
                $data['email'] = $entry[$key['id']]; 
            } 
            else if(strpos(strtolower($key['label']), 'tel') !== false || strpos(strtolower($key['label']), 'phone') !== false){
                $data['phone'] = $entry[$key['id']]; 
            }
        }
        
        $form_pot_fields = array();

        if($data['email'] && $data['email'] != ''){
            foreach($form_pot['fields'] as $key){
                if(is_numeric($key['id'])){
                    $form_pot_fields[$key['label']] = $key['id'];
                }
            }
            $entry_pot = GFAPI::get_entries($form_pot['id']);

            foreach($entry_pot as $entry){
                if($entry[$form_pot_fields['Email']] == $data['email']){
                    foreach($entry as $e_id => $e_key){
                        if($e_id == $form_pot_fields['Imię']){
                            $data['imie'] = $e_key;
                        } else if($e_id == $form_pot_fields['Nazwisko']){
                            $data['nazwisko'] = $e_key;
                        } else if($e_id == $form_pot_fields['Ulica']){
                            $data['ulica'] = $e_key;
                        } else if($e_id == $form_pot_fields['Numer budynku']){
                            $data['numb'] = $e_key;
                        } else if($e_id == $form_pot_fields['Numer mieszkania']){
                            $data['numm'] = $e_key;
                        } else if($e_id == $form_pot_fields['Kod pocztowy']){
                            $data['kod'] = $e_key;
                        } else if($e_id == $form_pot_fields['Miasto']){
                            $data['miasto'] = $e_key;
                        }
                    }
                }
            }
        }
        return $data;
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
    * Returns the HTML output as a string.
    * 
    * @return string @output 
    */
    public static function output($atts) {
        $dark_color = '#0f6489';
        $light_color = '#27a5ce';
        $text_color = 'color:' . self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], 'white') . ' !important;';
        $text_shadow_color = 'text-shadow: 2px 2px ' . self::findColor($atts['text_shadow_color_manual_hidden'], $atts['text_shadow_color'], 'black') . ' !important;';        

        if ($_POST['qr-code']){
            $data = self::findData($_POST['qr-code']);
        }
        $output = '';

        require_once plugin_dir_path(__FILE__) . '/../widgets/flags.php';
        if ($_POST['submit-btn']){

        } else {
            $output .= '
                <style>
                    body:has(#active_qr) :is(.menu-wrapper, .site-footer){
                        display:none !important;
                    }
                    .vc_row:has(#active_qr){
                        background-image: url("https://[trade_fair_domainadress]/wp-content/plugins/pwe-media/media/ticket-activation2.jpg");
                        height:90vh;
                        min-height: 900px;
                        display: flex;
                    }
                    .pwelement_'.self::$rnd_id.' :is(.one-input, .more-inputs){
                        margin-top: 18px;
                    }
                    .pwelement_'.self::$rnd_id.' :is(.one-input div, .more-inputs){
                        display:flex;
                        gap:12px;
                    }
                    .pwelement_'.self::$rnd_id.' .pwe-active_qr{
                        padding: 36px 72px;
                        background-color: white;
                        border-radius:50px;
                    }
                    .pwelement_'.self::$rnd_id.' input{
                        width: 100%;
                        margin: 0;
                        box-shadow: none;
                        border-radius: 5px;
                        border-color: '.$light_color.' !important;
                    }
                    .pwelement_'.self::$rnd_id.' input.input-disabled{
                        background-color: lightgrey;
                    }
                    .pwelement_'.self::$rnd_id.' .checkmark {
                        color: green;       
                        font-size: 27px;    
                        margin-left: 5px;   
                        font-weight: bold;  
                        vertical-align: middle;
                    }
                    .pwelement_'.self::$rnd_id.' .input-blocked button{
                        width: 100px;
                        border-radius: 10px;
                    }
                    .pwelement_'.self::$rnd_id.' .button-submit{
                        background-color: lightgreen;
                    }
                    .pwelement_'.self::$rnd_id.' .one-input input{
                        width: 760px;
                    }
                    .pwelement_'.self::$rnd_id.' div :has(input[name="street"], input[name="city"], input[name="country"]){
                        flex:2;
                    }
                    .pwelement_'.self::$rnd_id.' div :has(input[name="str-number"], input[name="zipcode"]){
                        flex:1
                    }
                    .pwelement_'.self::$rnd_id.'  .pwe-form-footer{
                        margin-top: 36px;
                        display: flex;
                        align-items: center;
                        justify-content: space-around;
                        text-transform: uppercase;
                        gap: 20px;
                    }
                    .pwelement_'.self::$rnd_id.'  .pwe-form-footer div{
                        width:50%;
                        text-align: -webkit-center;
                    }
                    .pwelement_'.self::$rnd_id.'  .pwe-form-footer h2{
                        color: '.$light_color.';
                        margin-top: 0;
                    }
                    .pwelement_'.self::$rnd_id.'  .pwe-form-footer h4{
                        color: '.$dark_color.';
                        margin-top: 10px;
                    }
                    .pwelement_'.self::$rnd_id.'  .pwe-form-footer button{
                        padding: 10px 20px;
                        border-radius: 25px;
                        background-color: '.$dark_color.';
                        font-size: 38px;
                        font-weight: 700;
                        color: white;
                        text-wrap: nowrap;
                    }     
                    .step-class{
                        position: absolute;
                        bottom: -50px;
                        right: 0;
                        font-size: 30px;
                        font-weight: 600;
                        color: white;
                    }
                    .novalid{
                        color:red;
                        font-size:12px;
                        font-weight:700;
                    }  
                </style>
                
                <div id="active_qr" class="pwe-active_qr">
                    <div class="form-container">
                        <form id="active_qr_form" action="" method="POST">
                            <div class="one-input input-blocked">
                                <label>'.
                                    self::languageChecker(
                                        <<<PL
                                            Adres email
                                        PL,
                                        <<<EN
                                            Email Adress
                                        EN
                                    )
                                .'</label>
                                <div>
                                    <input class="input-disabled" type="email" name="email" disabled required>
                                    <button type="button" class="button-edit">'.
                                    self::languageChecker(
                                        <<<PL
                                            edytuj
                                        PL,
                                        <<<EN
                                            edit
                                        EN
                                    )
                                .'</button>
                                    <button type="button" class="button-submit">'.
                                    self::languageChecker(
                                        <<<PL
                                            Zatwierdź
                                        PL,
                                        <<<EN
                                            Confirm
                                        EN
                                    )
                                .'</button>
                                </div>
                            </div>
                            <div class="one-input input-blocked">
                                <label>'.
                                    self::languageChecker(
                                        <<<PL
                                            Numer telefonu
                                        PL,
                                        <<<EN
                                            Phone Number
                                        EN
                                    )
                                .'</label>
                                <div>
                                    <input class="input-disabled" type="tel" name="tel" disabled required>
                                    <button type="button" class="button-edit">'.
                                    self::languageChecker(
                                        <<<PL
                                            edytuj
                                        PL,
                                        <<<EN
                                            edit
                                        EN
                                    )
                                .'</button>
                                    <button type="button" class="button-submit">'.
                                    self::languageChecker(
                                        <<<PL
                                            Zatwierdź
                                        PL,
                                        <<<EN
                                            Confirm
                                        EN
                                    )
                                .'</button>
                                </div>
                            </div>
                            <div class="one-input">
                                <label>'.
                                    self::languageChecker(
                                        <<<PL
                                            Imię i Nazwisko
                                        PL,
                                        <<<EN
                                            Full Name
                                        EN
                                    )
                                .'</label>
                                <div>
                                    <input type="text" name="name" required>
                                </div>
                            </div>
                            <div class="more-inputs">
                                <div>
                                    <label>'.
                                    self::languageChecker(
                                        <<<PL
                                            Ulica
                                        PL,
                                        <<<EN
                                            Street Name
                                        EN
                                    )
                                .'</label>
                                    <input type="text" name="street" required>
                                </div>
                                <div>
                                    <label>'.
                                    self::languageChecker(
                                        <<<PL
                                            Nr Budynku / mieszkania
                                        PL,
                                        <<<EN
                                            No. of Building / Apartment
                                        EN
                                    )
                                .'</label>
                                    <input type="text" name="str-number" required>
                                </div>
                                <div>
                                    <label>'.
                                    self::languageChecker(
                                        <<<PL
                                            Kod Pocztowy
                                        PL,
                                        <<<EN
                                            Zip Code
                                        EN
                                    )
                                .'</label>
                                    <input type="text" name="zipcode" required>
                                </div>
                            </div>
                            <div class="more-inputs">
                                <div>
                                    <label>'.
                                    self::languageChecker(
                                        <<<PL
                                            Miasto
                                        PL,
                                        <<<EN
                                            City
                                        EN
                                    )
                                .'</label>
                                    <input type="text" name="city" required>
                                </div>
                                <div>
                                    <label>'.
                                    self::languageChecker(
                                        <<<PL
                                            Kraj
                                        PL,
                                        <<<EN
                                            Country
                                        EN
                                    )
                                .'</label>
                                    <input type="text" name="country" required>
                                </div>
                            </div>
                            <div class="pwe-form-footer">
                                <div>
                                    <h2>'.
                                    self::languageChecker(
                                        <<<PL
                                            Sprawdź!
                                        PL,
                                        <<<EN
                                            Check it out!
                                        EN
                                    )
                                .'</h2>
                                    <h4>'.
                                    self::languageChecker(
                                        <<<PL
                                            na te dane dostaniesz potwierdzenie twojej aktywacji
                                        PL,
                                        <<<EN
                                            on this data you will receive confirmation of your activation
                                        EN
                                    )
                                .'</h4>
                                </div>
                                <button name="submit-btn" class="submit-btn" type="submit">'.
                                    self::languageChecker(
                                        <<<PL
                                            Aktywuj Bilet ➤
                                        PL,
                                        <<<EN
                                            Activate Ticket ➤
                                        EN
                                    )
                                .'</button>
                            </div>
                        </form>
                    </div>
                    <span class="step-class">'.
                                    self::languageChecker(
                                        <<<PL
                                            KROK 2/3
                                        PL,
                                        <<<EN
                                            STEP 2/3
                                        EN
                                    )
                                .'</span>
                </div>
                <script>
                    jQuery(document).ready(function($){
                        
                        if("'.$data['email'].'" != ""){            
                            $("input[name=email]").val("'.$data['email'].'");
                        } else {
                            $("input[name=email]").removeClass("input-disabled").prop("disabled", false);    
                        }

                        if("'.$data['phone'].'" != ""){
                            $("input[name=tel]").val("'.$data['phone'].'");
                        } else {
                            $("input[name=tel]").removeClass("input-disabled").prop("disabled", false);    
                        }

                        if("'.$data['imie'].'" != "" && "'.$data['nazwisko'].'" != ""){
                            $("input[name=name]").val("'.$data['imie'].' '.$data['nazwisko'].'");
                        }

                        $("input[name=street]").val("'.$data['ulica'].'");

                        if("'.$data['numb'].'" != "" && "'.$data['numm'].'" != ""){
                            $("input[name=str-number]").val("'.$data['numb'].' / '.$data['numm'].'");
                        }

                        $("input[name=zipcode]").val("'.$data['kod'].'");
                        $("input[name=city]").val("'.$data['miasto'].'");

                        $(".button-edit").on("click",function(){
                            $(this).parent().find("input").removeClass("input-disabled").prop("disabled", false);
                        });

                        $(".button-submit").on("click",function(){
                            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            const phoneRegex = /^[0-9+\-\s()]{7,15}$/;
                            $inputTarget = $(this).parent().find("input");

                            if(!$inputTarget.prop("disabled") && $inputTarget.attr("type") == "email" && emailRegex.test($inputTarget.val())){
                                $inputTarget.addClass("checked").prop("disabled", true).after("<span class=checkmark>✔</span>");
                            } else if(!$inputTarget.prop("disabled") && $inputTarget.attr("type") == "tel" && phoneRegex.test($inputTarget.val())){
                                $inputTarget.addClass("checked").prop("disabled", true).after("<span class=checkmark>✔</span>");
                            }
                        });
                        
                        $(".pwe-active_qr input").on("click",function(){
                            const isRemoved = $(this).parent().find(".novalid").remove();
                            if(isRemoved.length == 0 && $(this).parent().next().hasClass("novalid")){
                                $(this).parent().next().remove();
                            }
                        });

                        $(".submit-btn").on("click", function(event){
                            ';
                            $current_user = wp_get_current_user();
                            if ($current_user && $current_user->user_login == 'Marek') {
                                echo '<script>console.log("tak")</script>';
                            } else {
                                $output .= '
                                    event.preventDefault();
                                    window.location.href = "'.
                                        self::languageChecker(
                                            <<<PL
                                                https://[trade_fair_domainadress]/test-d/;
                                            PL,
                                            <<<EN
                                                https://[trade_fair_domainadress]/en/test-d/
                                            EN
                                        )
                                    .'";
                                ';
                            }
                    $output .= '
                        });
                    });
                </script>
            ';
        }

        return $output;
    }
}

?> 
