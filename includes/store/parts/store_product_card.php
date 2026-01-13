<?php

$output .= '
<div class="pwe-store__sections pwe-store__cards">';

    foreach ($categories as $category) {
        $output .= '
        <!-- Card section <------------------------------------------------------------------------------------------>
        <div id="'. str_replace("-", "", $category) .'Section" category="'. $category .'" class="pwe-store__'. $category .'-section pwe-store__section"> 
            <div class="pwe-store__services-cards">';

                foreach ($pwe_store_data as $product) {
                    $status = null;
                    if ($category == $product->prod_category && (self::lang_pl() ? !empty($product->prod_title_short_pl) : !empty($product->prod_title_short_en))) {
                        foreach ($store_options as $domain_options) {
                            if ($domain_options['domain'] === $current_domain) {
                                if (!empty($domain_options['options'])) {
                                    $options = json_decode($domain_options['options'], true);
                                    
                                    if (isset($options['products'])) {
                                        foreach ($options['products'] as $key => $option) {
                                            if ($product->prod_slug == $key) {
                                                $sold_out = $option['sold_out'] ? "sold-out" : "";
                                                $status_text = (self::lang_pl() ? $option['prod_image_text_pl'] : $option['prod_image_text_en']) 
                                                            ? (self::lang_pl() ? $option['prod_image_text_pl'] : $option['prod_image_text_en']) 
                                                            : "";
                                                $status = !empty($status_text) ? "status" : ""; 

                                                $disabled = !empty($status) && (stripos($option['prod_image_text_pl'], 'nie pokazuj') !== false) ? false : true;

                                                $custom_link = isset($option['custom-link']) ? $option['custom-link'] : "";
                                                // $custom_link = self::lang_pl() ? $custom_link : str_replace("/pl/", "/en/", $custom_link);
                                            }
                                        }
                                    }
                                }
                    
                                break;
                            }
                        }

                        if ($sold_out) {
                            $output .= '
                            <style>
                                .pwe-store__service-card-'. $product->prod_slug .'.sold-out .pwe-store__service-image:before {
                                    content: "'. (self::lang_pl() ? 'WYPRZEDANE' : 'SOLD OUT') .'";
                                }
                            </style>';
                        } else if (!empty($status)) {
                            $output .= '
                            <style>
                                .pwe-store__service-card-'. $product->prod_slug .'.status .pwe-store__service-image:before {
                                    content: "'. $status_text .'";
                                }
                            </style>';
                        } else if (!empty($product->prod_image_text_pl) && !empty($product->prod_image_text_en)) {
                            $status = "status";
                            $output .= '
                            <style>
                                .pwe-store__service-card-'. $product->prod_slug .'.status .pwe-store__service-image:before {
                                    content: "'. (self::lang_pl() ? $product->prod_image_text_pl : $product->prod_image_text_en) .'";
                                }
                            </style>';
                        }

                        $url_domain   = str_replace(".", "-", $current_domain);
                        $url_custom_pl = 'https://cap.warsawexpo.eu/public/uploads/domains/'. $url_domain .'/shop/'. $product->prod_slug .'/header_pl.webp';
                        $url_custom_en = 'https://cap.warsawexpo.eu/public/uploads/domains/'. $url_domain .'/shop/'. $product->prod_slug .'/header_en.webp';

                        if (self::lang_pl()) {
                            if (self::image_exists($url_custom_pl)) {
                                $img_url = $url_custom_pl;
                            } else {
                                $img_url = 'https://cap.warsawexpo.eu/public/uploads/shop/'. $product->prod_image_pl;
                            }
                        } else {
                            if (self::image_exists($url_custom_en)) {
                                $img_url = $url_custom_en;
                            } elseif (self::image_exists($url_custom_pl)) {
                                $img_url = $url_custom_pl;
                            } else {
                                $img_url = 'https://cap.warsawexpo.eu/public/uploads/shop/'. (!empty($product->prod_image_en) ? $product->prod_image_en : $product->prod_image_pl);
                            }
                        }

                        if (!$disabled && $options != null) continue;
 
                        $output .= '
                        <!-- Card item -->
                        <div class="pwe-store__service-card pwe-store__service-card-'. $product->prod_slug .' pwe-store__service '. $sold_out . ' ' . $status .'" category="'. $category .'" data-slug="'. $product->prod_slug .'">
                            <a class="pwe-store__service-card-wrapper" href="#" data-featured="'. $product->prod_slug .'">
                                <div class="pwe-store__service-image">
                                    <img
                                        src="'. $img_url .'" 
                                        alt="'. ( self::lang_pl() ? $product->prod_title_short_pl : $product->prod_title_short_en ) .'"
                                    >
                                </div>
                                <div class="pwe-store__service-content">
                                    <h4 class="pwe-store__service-name pwe-store__service-name-mailing">'. ( self::lang_pl() ? $product->prod_title_short_pl : $product->prod_title_short_en ) .'</h4>
                                    <div class="pwe-store__service-description">'. ( self::lang_pl() ? $product->prod_desc_short_pl : $product->prod_desc_short_en ) .'</div>
                                    <div class="pwe-store__service-footer">
                                        <div class="pwe-store__price">'. self::price($product, $store_options, $pwe_meta_data, $category, $current_domain) .'</div>
                                    </div>
                                </div>
                            </a>
                            <div class="pwe-store__btn-container">
                                <a href="#" class="pwe-store__more-button" data-featured="'. $product->prod_slug .'">'. (self::lang_pl() ? 'WIĘCEJ' : 'MORE') .'</a>';
                                if (!empty($custom_link)) {
                                    $output .= '<a href="'. $custom_link .'" target="_blank" class="pwe-store__buy-ticket-button">'. (self::lang_pl() ? 'KUP BILET' : 'BUY A TICKET') .'</a>';
                                } else {
                                    $output .= '<a href="#" class="pwe-store__reservation-button pwe-store__form-modal-open">'. (self::lang_pl() ? 'ZAREZERWUJ' : 'BOOK NOW') .'</a>';
                                }
                                $output .= '
                            </div>
                        </div>';
                    }
                }   

            $output .= '
            </div>';

        $output .= '
        </div>';
    }

    foreach ($packages_categories as $category) {
        $output .= '
        <!-- Card section <------------------------------------------------------------------------------------------>
        <div id="'. str_replace("-", "", $category) .'PackagesSection" category="'. $category .'-packages" class="pwe-store__'. $category .'-packages-section pwe-store__section"> 
            <div class="pwe-store__services-cards">';

                foreach ($pwe_store_packages_data as $package) {
                    if ($category == $package->packs_category && (self::lang_pl() ? !empty($package->packs_name_pl) : !empty($package->packs_name_en))) {
                        $output .= '
                        <!-- Card item -->
                        <div class="pwe-store__service-card pwe-store__service-card-'. $package->packs_slug .' pwe-store__service" category="'. $category .'-packages" data-slug="'. $product->prod_slug .'">
                            <a class="pwe-store__service-card-wrapper" href="#" data-featured="'. $package->packs_slug .'">
                                <div class="pwe-store__service-image">
                                    <img
                                        src="'. ( self::lang_pl() ? $package->packs_img_pl : (!empty($package->packs_img_en) ? $package->packs_img_en : $package->packs_img_pl)) .'" 
                                        alt="'. ( self::lang_pl() ? $package->packs_name_pl : $package->packs_name_en ) .'"
                                    >
                                </div>
                                <div class="pwe-store__service-content">
                                    <h4 class="pwe-store__service-name pwe-store__service-name-mailing">'. ( self::lang_pl() ? $package->packs_name_pl : $package->packs_name_en ) .'</h4>
                                    <div class="pwe-store__service-description">'. ( self::lang_pl() ? $package->packs_short_desc_pl : $package->packs_short_desc_en ) .'</div>
                                    <div class="pwe-store__service-footer">
                                        <div class="pwe-store__price">';
                                            $package_products_slug = $package->packs_data;
                                            $total_price = 0;
                                            $products = explode(' ', $package_products_slug);
                                        
                                            foreach ($products as $product_slug_with_quantity) {
                                                // Separating the product into slug and quantity
                                                $parts = explode('*', $product_slug_with_quantity);
                                                $product_slug = $parts[0];
                                                $quantity = isset($parts[1]) ? (int)$parts[1] : 1;

                                                foreach ($pwe_store_data as $product) {
                                                    if ($product->prod_slug === $product_slug) {   
                                                        $product_price = self::price($product, $store_options, $pwe_meta_data, $category, $current_domain, $num_only = true);
                                                        $total_price += $product_price * $quantity;
                                                        break;
                                                    }
                                                }
                                            }

                                            if ($package->packs_discount != null) {
                                                $discount = $total_price * ($package->packs_discount / 100);
                                                $discount_price = $total_price - $discount;
                                                $discount_price = number_format(self::round_price($discount_price), 0, ',', ' ');
                                            }
                                            $total_price = number_format(self::round_price($total_price), 0, ',', ' ');        
    
                                            if (!empty($discount_price)) {
                                                $output .= '<stan class="pwe-store__discount-price">'. $discount_price . (self::lang_pl() ? " zł netto" : " € net") .'</stan>';
                                            }
                                            $output .= '<stan class="pwe-store__regular-price '. (!empty($discount_price) ? 'unactive' : '') .'">'. $total_price . (self::lang_pl() ? " zł netto" : " € net") .'</stan>';
                                        $output .= '
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <div class="pwe-store__btn-container">
                                <a href="#" class="pwe-store__more-button" data-featured="'. $package->packs_slug .'">'. (self::lang_pl() ? 'WIĘCEJ' : 'MORE') .'</a>
                                <a href="#" class="pwe-store__reservation-button pwe-store__form-modal-open">'. (self::lang_pl() ? 'ZAREZERWUJ' : 'BOOK NOW') .'</a>
                            </div>
                        </div>';
                    }
                }

            $output .= '
            </div>';

        $output .= '
        </div>';
    }



    if (empty($categories)) {
        $output .= '
        <div style="margin-top: 36px; text-align: center; font-size: 24px; font-weight: 600;">'. 
            (self::lang_pl() ? "Przepraszamy, produkty tymczasowo niedostępne" : "Sorry, products temporarily unavailable") .'
        </div>';
    } else {
        $output .= '
        <div style="margin-top: 36px; font-size: 14px; background: white; border-radius: 8px; box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.1); padding: 18px;">'. 
            (self::lang_pl() ? 
            '<p style="margin: 0; font-size: 14px;">Powyższa wycena usług stanowi wycenę orientacyjną. Ostateczna cena realizacji usługi zostanie ustalona indywidualnie po kontakcie ze strony przedstawiciela PTAK Warsaw Expo, który nastąpi po wypełnieniu formularza zgłoszeniowego. Wypełnienie formularza nie zobowiązuje do zakupu usługi.</p>
            <p style="font-size: 14px;">Powyższe nie stanowi oferty w rozumieniu Kodeksu cywilnego. Wskazane ceny orientacyjne są cenami netto, do których należy doliczyć obowiązujący podatek VAT.</p>' 
            : 
            '<p style="margin: 0; font-size: 14px;">The above price list for services is an approximate price. The final price for the service will be determined individually after contact with a representative of PTAK Warsaw Expo, which will take place after filling out the application form. Filling out the form does not oblige you to purchase the service.</p>
            <p style="font-size: 14px;">The above does not constitute an offer within the meaning of the Civil Code. The indicated approximate prices are net prices, to which the applicable VAT should be added.</p>'
            ) .'
        </div>';
    }

$output .= '
</div>';

return $output;