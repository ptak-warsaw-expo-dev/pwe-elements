<?php

$output .= '
<!-- Package product modal -->
<div id="pweStoreModal" class="pwe-store__modal" style="display: none;">
    <div class="pwe-store__modal-content">
        <span class="pwe-store__modal-close-btn">&times;</span>
        <div class="pwe-store__modal-content-placeholder">
            <!-- selectItem -->
        </div>
    </div>
</div>

<!-- Contact form modal -->
<div id="pweStoreFormModal" class="pwe-store__form-modal">
    <div class="pwe-store__form-modal-content">
        <span class="pwe-store__form-modal-close">&times;</span>
        <h2 class="pwe-store__form-modal-title">'. 
            (self::lang_pl() ? 
                'ZYSKAJ PRZEWAGĘ NAD KONKURENCJĄ I WYPEŁNIJ FORMULARZ' : 
                'GAIN AN EDGE OVER YOUR COMPETITION AND FILL OUT THE FORM'
            ).'
        </h2>
        <div class="pwe-store__form-modal-form">
            <label for="email">'. (self::lang_pl() ? 'Adres email' : 'Email address') .'</label>
            <input 
                type="email" 
                class="pwe-store__form-modal-input_email" 
                placeholder="'. (self::lang_pl() ? 'Adres email' : 'Email address') .'"
                required 
                autocomplete="email"
            >
            <label for="tel">'. (self::lang_pl() ? 'Numer telefonu' : 'Phone number') .'</label>
            <input 
                type="tel" 
                class="pwe-store__form-modal-input_tel" 
                placeholder="'. (self::lang_pl() ? 'Numer telefonu' : 'Phone number') .'"
                required 
                autocomplete="tel"
            >
            <div class="pwe-store__form-modal-consent">
                <input 
                    type="checkbox" 
                    class="pwe-store__form-modal-consent-checkbox"
                >
                <p>'. 
                    (self::lang_pl() ? 
                        'Wyrażam zgodę na przetwarzanie przez PTAK WARSAW EXPO sp. z o.o. moich danych osobowych w celach marketingowych i wysyłki wiadomości. <span class="show-consent">(Więcej)</span>' : 
                        'I consent to the processing of my personal data by PTAK WARSAW EXPO sp. z o.o. for marketing purposes and sending messages. <span class="show-consent">(More)</span>'
                    ).'
                </p>
            </div>
            <div class="pwe-store__form-modal-consent-container">
                <p class="pwe-store__form-modal-consent-desc" style="display: none;">'. 
                    (self::lang_pl() ? 
                        'Wyrażam zgodę na przetwarzanie przez PTAK WARSAW EXPO sp. z o.o. moich danych osobowych, tj. 1) adres e-mail 2) nr telefonu w celach wysyłki wiadomości marketingowych i handlowych związanych z produktami i usługami oferowanymi przez Ptak Warsaw Expo sp. z o.o. za pomocą środków komunikacji elektronicznej lub bezpośredniego porozumiewania się na odległość, w tym na otrzymywanie informacji handlowych, stosownie do treści Ustawy z dnia 18 lipca 2002 r. o świadczeniu usług drogą elektroniczną. Wiem, że wyrażenie zgody jest dobrowolne, lecz konieczne w celu dokonania rejestracji. Zgodę mogę wycofać w każdej chwili.' : 
                        'I consent to the processing of my personal data by PTAK WARSAW EXPO sp. z o.o., i.e. 1) e-mail address 2) telephone number for the purposes of sending marketing and commercial messages related to products and services offered by Ptak Warsaw Expo sp. z o.o. by means of electronic communication or direct remote communication, including receiving commercial information, in accordance with the Act of 18 July 2002 on the provision of services by electronic means. I know that giving consent is voluntary, but necessary for the purpose of registration. I can withdraw my consent at any time.'
                    ).'
                </p>
            </div>
        </div>
        <button class="pwe-store__form-modal-submit">'. (self::lang_pl() ? 'Zatwierdź' : 'Confirm') .'</button>
    </div>
</div>';