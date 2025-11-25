<?php

if ($map_dynamic_3d == true) {

    $output .= '
    <div id="pweMap" class="pwe-map">
        <div class="pwe-map__wrapper">
            <div class="pwe-map__staticts">
                <h3 class="pwe-map__title text-accent-color">'. $map_custom_title .'</h3>
                <div class="pwe-map__rounded-stat">
                    <div class="pwe-map__rounded-element text-accent-color">
                        <p style="font-weight: 800; font-size: 21px;">
                            <span class="countup" data-count="'. $map_number_visitors .'">0</span>
                        </p>
                        <p style="font-size:12px">'. PWEMapDynamic::multi_translation("visitors") .'</p>
                    </div>
                    <div class="pwe-map__rounded-element pwe-map__rounded-element-country">
                        <p style="font-weight: 800; font-size: 27px;">
                            <span class="countup" data-count="'. $map_number_countries .'">0</span>
                        </p>
                        <p style="font-size:12px">'. PWEMapDynamic::multi_translation("countries") .'</p>
                    </div>
                </div>
                <div class="pwe-map__stats-container desktop">
                    <div class="pwe-map__stats-element">
                        <p class="text-accent-color pwe-map__stats-element-title">'. PWEMapDynamic::multi_translation("poland") .'
                            <span class="countup" data-count="'. floor($map_number_visitors / 100 * $map_percent_polish_visitors) .'">0</span>
                        </p>
                        <p class="pwe-map__stats-element-desc"><span class="countup" data-count="'. $map_percent_polish_visitors .'">0</span> %</p>
                    </div>
                    <div class="pwe-map__stats-element">
                        <p class="text-accent-color pwe-map__stats-element-title">'. PWEMapDynamic::multi_translation("abroad") .'
                            <span class="countup" data-count="'. ($map_number_visitors - floor($map_number_visitors / 100 * $map_percent_polish_visitors)) .'">0</span>
                        </p>
                        <p class="pwe-map__stats-element-desc">
                        <span class="countup" data-count="'. (100 - $map_percent_polish_visitors) .'">0</span> %</p>
                    </div>
                    <div class="pwe-map__stats-element">
                        <p class="text-accent-color pwe-map__stats-element-title">
                            <span class="countup" data-count="'. $map_exhibition_space .'">0</span> m<sup>2</sup>
                        </p>
                        <p class="pwe-map__stats-element-desc">'. PWEMapDynamic::multi_translation("exhibition_space") .'</p>
                    </div>
                    <div class="pwe-map__stats-element">
                        <p class="text-accent-color pwe-map__stats-element-title">
                            <span class="countup" data-count="'. $map_number_exhibitors .'">0</span>
                        </p>
                        <p class="pwe-map__stats-element-desc">'. PWEMapDynamic::multi_translation("exhibitors") .'</p>
                    </div>
                </div>
            </div>

            <div id="container-3d" class="pwe-map__container-3d"></div>

            <div class="pwe-map__stats-container mobile">
                <div class="pwe-map__stats-element pwe-map__stats-element-55">
                    <p class="text-accent-color pwe-map__stats-element-title">'. PWEMapDynamic::multi_translation("poland") .'
                        <span class="countup" data-count="'. floor($map_number_visitors / 100 * $map_percent_polish_visitors) .'">0</span>
                    </p>
                    <p class="pwe-map__stats-element-desc"><span class="countup" data-count="'. $map_percent_polish_visitors .'">0</span> %</p>
                </div>
                <div class="pwe-map__stats-element pwe-map__stats-element-45">
                    <p class="text-accent-color pwe-map__stats-element-title">
                        <span class="countup" data-count="'. $map_number_exhibitors .'">0</span>
                    </p>
                    <p class="pwe-map__stats-element-desc">'. PWEMapDynamic::multi_translation("exhibitors") .'</p>
                </div>
                <div class="pwe-map__stats-element pwe-map__stats-element-55">
                    <p class="text-accent-color pwe-map__stats-element-title">'. PWEMapDynamic::multi_translation("abroad") .'
                        <span class="countup" data-count="'. ($map_number_visitors - floor($map_number_visitors / 100 * $map_percent_polish_visitors)) .'">0</span>
                    </p>
                    <p class="pwe-map__stats-element-desc">
                    <span class="countup" data-count="'. (100 - $map_percent_polish_visitors) .'">0</span> %</p>
                </div>
                <div class="pwe-map__stats-element pwe-map__stats-element-45">
                    <p class="text-accent-color pwe-map__stats-element-title">
                        <span class="countup" data-count="'. $map_exhibition_space .'">0</span> m<sup>2</sup>
                    </p>
                    <p class="pwe-map__stats-element-desc">'. PWEMapDynamic::multi_translation("exhibition_space") .'</p>
                </div>

            </div>

            <div class="pwe-map__logotypes">
                    <div class="pwe-map__logo-container">'.
                        self::languageChecker('<img src="/doc/logo-color.webp"/>', '<img src="/doc/logo-color-en.webp"/>');
                        if (is_array($map_more_logotypes)){
                            foreach($map_more_logotypes as $single_logo){
                                $output .= '<img src="' . $single_logo . '"/>';
                            }
                            $output .= '<p class="pwe-map__logotypes-data" style="text-align: right;">[trade_fair_date_multilang]</p>';
                        } else {
                            $output .= '<p class="pwe-map__logotypes-data" style="text-align: center;">[trade_fair_date_multilang]</p>';
                        }
                        $output .= '
                    </div>';

                    if (!empty($map_number_visitors_previous) || !empty($map_number_exhibitors_previous) || !empty($map_exhibition_space_previous)) {
                        $output .= '
                        <div class="pwe-map__stats-diagram desktop">
                            <!-- Years -->
                            <div class="pwe-map__stats-diagram-years-container">
                                <div class="pwe-map__stats-diagram-year">
                                    <div class="pwe-map__stats-diagram-year-box"></div>
                                    <span>'. $map_year_previous .'</span>
                                </div>
                                <div class="pwe-map__stats-diagram-year">
                                    <div class="pwe-map__stats-diagram-year-box"></div>
                                    <span>'. $map_year .'</span>
                                </div>
                            </div>

                            <!-- Bars -->
                            <div class="pwe-map__stats-diagram-bars-container">
                                <!-- Bar 1 -->
                                <div class="pwe-map__stats-diagram-bars">
                                    <div class="pwe-map__stats-diagram-bars-wrapper">
                                        <div class="pwe-map__stats-diagram-bar visitors">
                                            <div class="pwe-map__stats-diagram-bar-item" data-count="'. $map_number_visitors_previous_percentage .'">
                                                <div class="pwe-map__stats-diagram-bar-number"><span class="countup" data-count="'. $map_number_visitors_previous .'">0</span></div>
                                            </div>
                                        </div>
                                        <div class="pwe-map__stats-diagram-bar visitors">
                                            <div class="pwe-map__stats-diagram-bar-item" data-count="'. $map_number_visitors_percentage .'">
                                                <div class="pwe-map__stats-diagram-bar-number"><span class="countup" data-count="'. $map_number_visitors .'">0</span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="pwe-map__stats-diagram-bars-label">'. PWEMapDynamic::multi_translation("visitors_2") .'</p>
                                </div>

                                <!-- Bar 2 -->
                                <div class="pwe-map__stats-diagram-bars">
                                    <div class="pwe-map__stats-diagram-bars-wrapper">
                                        <div class="pwe-map__stats-diagram-bar exhibitors">
                                            <div class="pwe-map__stats-diagram-bar-item" data-count="'. $map_number_exhibitors_previous_percentage .'">
                                                <div class="pwe-map__stats-diagram-bar-number"><span class="countup" data-count="'. $map_number_exhibitors_previous .'">0</span></div>
                                            </div>
                                        </div>
                                        <div class="pwe-map__stats-diagram-bar exhibitors">
                                            <div class="pwe-map__stats-diagram-bar-item" data-count="'. $map_number_exhibitors_percentage .'">
                                                <div class="pwe-map__stats-diagram-bar-number"><span class="countup" data-count="'. $map_number_exhibitors .'">0</span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="pwe-map__stats-diagram-bars-label">'. PWEMapDynamic::multi_translation("exhibitors_2") .'</p>
                                </div>

                                <!-- Bar 3 -->
                                <div class="pwe-map__stats-diagram-bars">
                                    <div class="pwe-map__stats-diagram-bars-wrapper">
                                        <div class="pwe-map__stats-diagram-bar area">
                                            <div class="pwe-map__stats-diagram-bar-item" data-count="'. $map_exhibition_space_previous_percentage .'">
                                                <div class="pwe-map__stats-diagram-bar-number"><span class="countup" data-count="'. $map_exhibition_space_previous .'">0</span></div>
                                            </div>
                                        </div>
                                        <div class="pwe-map__stats-diagram-bar area">
                                            <div class="pwe-map__stats-diagram-bar-item" data-count="'. $map_exhibition_space_percentage .'">
                                                <div class="pwe-map__stats-diagram-bar-number"><span class="countup" data-count="'. $map_exhibition_space .'">0</span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="pwe-map__stats-diagram-bars-label">'. PWEMapDynamic::multi_translation("surface") .' m<sup>2</sup></p>
                                </div>
                            </div>
                        </div>';
                    }

            $output .= '
            </div>
        </div>';

        // $output .='
        // <div class="pwe-map__stats-diagram mobile">
        //     <!-- Years -->
        //     <div class="pwe-map__stats-diagram-years-container">
        //         <div class="pwe-map__stats-diagram-year">
        //             <div class="pwe-map__stats-diagram-year-box"></div>
        //             <span>'. $map_year_previous .'</span>
        //         </div>
        //         <div class="pwe-map__stats-diagram-year">
        //             <div class="pwe-map__stats-diagram-year-box"></div>
        //             <span>'. $map_year .'</span>
        //         </div>
        //     </div>

        //     <!-- Bars -->
        //     <div class="pwe-map__stats-diagram-bars-container">
        //         <!-- Bar 1 -->
        //         <div class="pwe-map__stats-diagram-bars">
        //             <div class="pwe-map__stats-diagram-bars-wrapper">
        //                 <div class="pwe-map__stats-diagram-bar">
        //                     <div class="pwe-map__stats-diagram-bar-item" data-count="'. $map_number_visitors_previous_percentage .'">
        //                         <div class="pwe-map__stats-diagram-bar-number"><span class="countup" data-count="'. $map_number_visitors_previous .'">0</span></div>
        //                     </div>
        //                 </div>
        //                 <div class="pwe-map__stats-diagram-bar">
        //                     <div class="pwe-map__stats-diagram-bar-item" data-count="'. $map_number_visitors_percentage .'">
        //                         <div class="pwe-map__stats-diagram-bar-number"><span class="countup" data-count="'. $map_number_visitors .'">0</span></div>
        //                     </div>
        //                 </div>
        //             </div>
        //             <p class="pwe-map__stats-diagram-bars-label">'. self::languageChecker('Odwiedzający', 'Visitors') .'</p>
        //         </div>

        //         <!-- Bar 2 -->
        //         <div class="pwe-map__stats-diagram-bars">
        //             <div class="pwe-map__stats-diagram-bars-wrapper">
        //                 <div class="pwe-map__stats-diagram-bar">
        //                     <div class="pwe-map__stats-diagram-bar-item" data-count="'. $map_number_exhibitors_previous_percentage .'">
        //                         <div class="pwe-map__stats-diagram-bar-number"><span class="countup" data-count="'. $map_number_exhibitors_previous .'">0</span></div>
        //                     </div>
        //                 </div>
        //                 <div class="pwe-map__stats-diagram-bar">
        //                     <div class="pwe-map__stats-diagram-bar-item" data-count="'. $map_number_exhibitors_percentage .'">
        //                         <div class="pwe-map__stats-diagram-bar-number"><span class="countup" data-count="'. $map_number_exhibitors .'">0</span></div>
        //                     </div>
        //                 </div>
        //             </div>
        //             <p class="pwe-map__stats-diagram-bars-label">'. self::languageChecker('Wystawcy', 'Exhibitors') .'</p>
        //         </div>

        //         <!-- Bar 3 -->
        //         <div class="pwe-map__stats-diagram-bars">
        //             <div class="pwe-map__stats-diagram-bars-wrapper">
        //                 <div class="pwe-map__stats-diagram-bar">
        //                     <div class="pwe-map__stats-diagram-bar-item" data-count="'. $map_exhibition_space_previous_percentage .'">
        //                         <div class="pwe-map__stats-diagram-bar-number"><span class="countup" data-count="'. $map_exhibition_space_previous .'">0</span> m<sup>2</sup></div>
        //                     </div>
        //                 </div>
        //                 <div class="pwe-map__stats-diagram-bar">
        //                     <div class="pwe-map__stats-diagram-bar-item" data-count="'. $map_exhibition_space_percentage .'">
        //                         <div class="pwe-map__stats-diagram-bar-number"><span class="countup" data-count="'. $map_exhibition_space .'">0</span> m<sup>2</sup></div>
        //                     </div>
        //                 </div>
        //             </div>
        //             <p class="pwe-map__stats-diagram-bars-label">'. self::languageChecker('Powierzchnia', 'Surface') .'</p>
        //         </div>
        //     </div>
        // </div>';

    $output .='
    </div>';
} else {
    $output .= '
    <div id="mapa" class="pwe-container-mapa">
        <div class="pwe-mapa-staticts">
            <h2 class="text-accent-color">'. $map_custom_title .'</h2>
            <div class="pwe-mapa-rounded-stat">
                <div class="pwe-mapa-rounded-element text-accent-color">
                    <p style="font-weight: 800; font-size: 21px;">'. $map_number_visitors .'</p>
                    <p style="font-size:12px">'. PWEMapDynamic::multi_translation("visitors") .'</p>
                </div>
                <div class="pwe-mapa-rounded-element pwe-mapa-rounded-element-country">
                    <p style="font-weight: 800; font-size: 27px;">'. $map_number_countries .'</p>
                    <p style="font-size:12px">'. PWEMapDynamic::multi_translation("countries") .'</p>
                </div>
            </div>
            <div class="pwe-mapa-stats-container">
                <div class="pwe-mapa-stats-element">
                    <p class="text-accent-color pwe-mapa-stats-element-title">'. PWEMapDynamic::multi_translation("poland") .' '. floor($map_number_visitors / 100 * $map_percent_polish_visitors) .'
                    </p>
                    <p class="pwe-mapa-stats-element-desc">'. $map_percent_polish_visitors .' %</p>
                </div>
                <div class="pwe-mapa-stats-element">
                    <p class="text-accent-color pwe-mapa-stats-element-title">'. PWEMapDynamic::multi_translation("abroad") .' '. ($map_number_visitors - floor($map_number_visitors / 100 * $map_percent_polish_visitors)) .'
                    </p>
                    <p class="pwe-mapa-stats-element-desc">'. (100 - $map_percent_polish_visitors) .' %</p>
                </div>
                <div class="mobile-estymacje-image"></div>
                <div class="pwe-mapa-stats-element">
                    <p class="text-accent-color pwe-mapa-stats-element-title">'. $map_exhibition_space .' m<sup>2</sup></p>
                    <p class="pwe-mapa-stats-element-desc">'. PWEMapDynamic::multi_translation("exhibition_space") .'</p>
                </div>
                <div class="pwe-mapa-stats-element">
                    <p class="text-accent-color pwe-mapa-stats-element-title">'. $map_number_exhibitors .'</p>
                    <p class="pwe-mapa-stats-element-desc">'. PWEMapDynamic::multi_translation("exhibitors") .'</p>
                </div>
            </div>
        </div>';

        $output .= '
        <div class="pwe-mapa-right">
            <div class="pwe-mapa-logo-container">'.
                self::languageChecker('<img src="/doc/logo-color.webp"/>', '<img src="/doc/logo-color-en.webp"/>');
                if (is_array($map_more_logotypes)){
                    foreach($map_more_logotypes as $single_logo){
                        $output .= '<img src="' . $single_logo . '"/>';
                    }
                    $output .= '<p class="pwe-mapa-right-data" style="text-align: right;">[trade_fair_date_multilang]</p>';
                } else {
                    $output .= '<p class="pwe-mapa-right-data" style="text-align: center;">[trade_fair_date_multilang]</p>';
                }
                $output .= '
            </div>
            <div class="pwe-mapa-rounded-element pwe-mapa-rounded-element-country-right">
                <p style="font-weight: 800; font-size: 24px;">'. $map_number_countries .'</p>
                <p style="font-size:12px">'. PWEMapDynamic::multi_translation("countries") .'</p>
            </div>
        </div>
    </div>';
}