<?php

$output .= '
<div id="pweMap" class="pwe-map">
    <div class="pwe-map__wrapper">

        <div class="pwe-map__title-section">
            <h2 class="pwe-map__title">'. $map_custom_title .'</h2>
            <p class="pwe-map__subtitle">'. self::multi_translation("numbers") .'</p>
        </div>

        <div class="pwe-map__stats-section">
            <div class="pwe-map__stats-diagram">
                <div class="pwe-map__stats-diagram-container">
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
                                <div class="pwe-map__stats-diagram-bar">
                                    <div class="pwe-map__stats-diagram-bar-item" data-count="'. $map_number_visitors_previous_percentage .'">
                                        <div class="pwe-map__stats-diagram-bar-number"><span class="countup" data-count="'. $map_number_visitors_previous .'">0</span></div>
                                    </div>
                                </div>
                                <div class="pwe-map__stats-diagram-bar">
                                    <div class="pwe-map__stats-diagram-bar-item" data-count="'. $map_number_visitors_percentage .'">
                                        <div class="pwe-map__stats-diagram-bar-number"><span class="countup" data-count="'. $map_number_visitors .'">0</span></div>
                                    </div>
                                </div>
                            </div>
                            <p class="pwe-map__stats-diagram-bars-label">'. self::multi_translation("visitors_2") .'</p>
                        </div>

                        <!-- Bar 2 -->
                        <div class="pwe-map__stats-diagram-bars">
                            <div class="pwe-map__stats-diagram-bars-wrapper">
                                <div class="pwe-map__stats-diagram-bar">
                                    <div class="pwe-map__stats-diagram-bar-item" data-count="'. $map_number_exhibitors_previous_percentage .'">
                                        <div class="pwe-map__stats-diagram-bar-number"><span class="countup" data-count="'. $map_number_exhibitors_previous .'">0</span></div>
                                    </div>
                                </div>
                                <div class="pwe-map__stats-diagram-bar">
                                    <div class="pwe-map__stats-diagram-bar-item" data-count="'. $map_number_exhibitors_percentage .'">
                                        <div class="pwe-map__stats-diagram-bar-number"><span class="countup" data-count="'. $map_number_exhibitors .'">0</span></div>
                                    </div>
                                </div>
                            </div>
                            <p class="pwe-map__stats-diagram-bars-label">'. self::multi_translation("exhibitors_2") .'</p>
                        </div>

                        <!-- Bar 3 -->
                        <div class="pwe-map__stats-diagram-bars">
                            <div class="pwe-map__stats-diagram-bars-wrapper">
                                <div class="pwe-map__stats-diagram-bar">
                                    <div class="pwe-map__stats-diagram-bar-item" data-count="'. $map_exhibition_space_previous_percentage .'">
                                        <div class="pwe-map__stats-diagram-bar-number"><span class="countup" data-count="'. $map_exhibition_space_previous .'">0</span> m<sup>2</sup></div>
                                    </div>
                                </div>
                                <div class="pwe-map__stats-diagram-bar">
                                    <div class="pwe-map__stats-diagram-bar-item" data-count="'. $map_exhibition_space_percentage .'">
                                        <div class="pwe-map__stats-diagram-bar-number"><span class="countup" data-count="'. $map_exhibition_space .'">0</span> m<sup>2</sup></div>
                                    </div>
                                </div>
                            </div>
                            <p class="pwe-map__stats-diagram-bars-label">'. self::multi_translation("surface") .'</p>
                        </div>
                    </div>
                </div>

                <!-- Countries -->
                <div class="pwe-map__stats-diagram-countries-container">
                    <div class="pwe-map__stats-diagram-countries pwe-map__stats-number-box">
                        <h2><span class="countup" data-count="'. $map_number_countries .'">0</span></h2>
                        <p>'. self::multi_translation("countries") .'</p>
                    </div>
                </div>
            </div>

            <div class="pwe-map__stats-number-container">
                <div class="pwe-map__stats-number-box">
                    <h2><span class="countup" data-count="'. $map_number_visitors .'">0</span></h2>
                    <div class="pwe-map__stats-number-box-text">
                        <span>+</span>
                        <p>'. self::multi_translation("visitors") .'</p>
                    </div>
                </div>

                <div class="pwe-map__stats-number-box">
                    <h2><span class="countup" data-count="'. $map_number_exhibitors .'">0</span></h2>
                    <div class="pwe-map__stats-number-box-text">
                        <span>+</span>
                        <p>'. self::multi_translation("exhibitors") .'</p>
                    </div>
                </div>

                <div class="pwe-map__stats-number-box">
                    <h2><span class="countup" data-count="'. $map_exhibition_space .'">0</span> m<sup>2</sup></h2>
                    <div class="pwe-map__stats-number-box-text">
                        <span>+</span>
                        <p>'. self::multi_translation("exhibition_space") .'</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="container-3d" class="pwe-map__container-3d"></div>

    </div>
</div>';