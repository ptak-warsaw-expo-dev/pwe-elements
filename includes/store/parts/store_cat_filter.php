<?php

if (!empty($categories)) {
    $output .= '
    <div class="pwe-store__category">
        <div class="pwe-store__category-wrapper">
            <div class="pwe-store__category-text"><p>'. (self::lang_pl() ? 'FILTRUJ:' : 'FILTER:') .'</p></div>
            <div class="pwe-store__category-items">';
                foreach ($categories as $category) {
                    if ($category == "test") continue;
                    $output .= '
                    <div id="'. $category .'" class="pwe-store__category-item">
                        <p class="pwe-uppercase">'. 
                            (self::lang_pl() ? 
                            'USŁUGI '. str_replace("marketing", "marketingowe", str_replace("-", " ", $category)) : 
                            str_replace("-", " ", $category) .' SERVICES') .'
                        </p>
                    </div>';
                }
                $output .= '
                <div id="packages" class="pwe-store__category-item dropdown">
                    <p class="pwe-uppercase">'. (self::lang_pl() ? 'Pakiety' : 'Packages') .'<span class="arrow">‹</span></p>
                    <ul class="packages-submenu pwe-uppercase">';
                        foreach ($packages_categories as $category) {
                            $output .= '<li id="'. $category .'-packages" class="pwe-store__category-item">'. (self::lang_pl() ? str_replace("marketing", "marketingowe", str_replace("-", " ", $category)) : str_replace("-", " ", $category)) .'</li>';
                        }
                $output .= '
                    </ul>
                </div>';
                
                $output .= '
            </div>
        </div>
    </div>';
}

return $output;