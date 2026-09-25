<?php

if ($current_domain === "warsawexpo.eu") {
    $output .= '
    <div class="pwe-store__fairs">
        <div class="pwe-store__fairs-arrow-back"><span>WRÓĆ</span></div>
        <div class="pwe-store__fairs-search">
            <h4>Wyszukaj wydarzenie albo wybierz z listy</h4>
            <input class="pwe-store__fairs-search-input" type="text">
        </div>
        <div class="pwe-store__fairs-items">';
        $all_editions = self::fairs_array();
        if (!empty($all_editions)) {

            $all_fairs = array_merge($all_editions['edition_1'], $all_editions['edition_2'], $all_editions['edition_3'], $all_editions['edition_b2c']);

            // Sorting the array based on date
            usort($all_fairs, function ($a, $b) {
                // If date is missing in one of the elements, this element goes to the end of the array
                if (empty($a['date']) && !empty($b['date'])) {
                    return 1;
                }
                if (!empty($a['date']) && empty($b['date'])) {
                    return -1;
                }
        
                // If both dates exist, we compare them
                $dateA = isset($a['date']) ? str_replace('/', '-', $a['date']) : '';
                $dateB = isset($b['date']) ? str_replace('/', '-', $b['date']) : '';
                
                // Convert date to timestamp
                $timestampA = strtotime($dateA);
                $timestampB = strtotime($dateB);
        
                return $timestampA - $timestampB;
            });

            foreach ($all_fairs as $fair) {
                if (isset($fair['domain'], $fair['name'], $fair['desc'], $fair['date'], $fair['edition']) && 
                    !empty($fair['domain']) && $fair['domain'] !== "" && 
                    !empty($fair['name']) && $fair['name'] !== "" && 
                    !empty($fair['desc']) && $fair['desc'] !== "" && 
                    !empty($fair['date']) && $fair['date'] !== "" && 
                    !empty($fair['edition']) && $fair['edition'] !== "") {
                
                    $output .= '
                    <div 
                        class="pwe-store__fairs-item" 
                        id="'. preg_replace('/\.[^.]*$/', '', $fair['domain']) .'" 
                        data-name="'. $fair['name'] .'" 
                        data-tooltip="'. $fair['desc'] .'" 
                        data-date="'. $fair['date'] .'" 
                        data-edition="'. $fair['edition'] .'" 
                        data-domain="'. $fair['domain'] .'" 
                        style="background-image: url(&quot;https://'. $fair['domain'] .'/doc/kafelek.jpg&quot;);"
                    >
                    </div>';
                } else {
                    if (current_user_can('administrator')) {
                        echo '<script>console.log("Brak danych dla: '. $fair['domain'] .'")</script>';
                    }
                }
            }
            
        } else { $output .= '<p style="position: absolute; left: 50%; transform: translate(-50%, 0); text-align: center;">Przepraszamy, lista targów jest tymczasowo niedostępna. =(</p>'; }
        $output .= '
        </div>
    </div>';
}

return $output;