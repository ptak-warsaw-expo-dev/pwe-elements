<?php

$output .= '
    <style>
        .pwe-header-left-time {
            z-index: 1;
            display: flex;
            justify-content: center;
        }
        .pwe-widget-info-header {
            position: absolute;
            left: 36px;
            top: 40%;
            z-index: 3;
            color: white;
            padding: 8px;
            background-color: rgba(0, 0, 0, 0.6);
            border: 5px solid rgba(255, 255, 255, 0.63);
        }
        @media (max-width:1250px) {
            .pwe-widget-info-header {
                position: static;
                margin: 0 0 36px;
            }
        }
    </style>';

    $output .= '
    <div class="pwe-header-left-time">
        <div class="pwe-widget-info-header">
            <div>
                <div style="display:flex; align-items:center; margin:8px 0 0 0; opacity: 100%;">
                    <img src="/wp-content/plugins/pwe-media/media/car-white.png" alt="car white" width="30" height="30" />
                    <p style="margin:0 0 0 10px;">
                        <span style="font-weight: bold; font-size:18px; color:white;">'. 
                            self::languageChecker(
                                <<<PL
                                DARMOWY PARKING
                                PL,
                                <<<EN
                                FREE PARKING
                                EN
                            )
                        .'</span>
                    </p>
                </div>
                <div style="display:flex; align-items:center; margin:8px 0 0 0; opacity: 100%;">
                    <img src="/wp-content/plugins/pwe-media/media/timer-white.png" alt="clock" width="30" height="30" />
                    <p style="margin:0 0 0 10px; font-size:18px;">
                        <span style="font-weight: bold;">'. 
                            self::languageChecker(
                                <<<PL
                                GODZINY OTWARCIA:
                                PL,
                                <<<EN
                                OPENING HOURS:
                                EN
                            )
                        .'</span>
                    </p>
                </div>
            </div>
            <div style="margin-left: 40px";>
                <div>
                    <p style="margin: 0px 0; line-height: 1.2;font-size:16px; text-align:right;">
                        <span style="font-weight: bold;">'. 
                            self::languageChecker(
                                <<<PL
                                [trade_fair_date]
                                PL,
                                <<<EN
                                [trade_fair_date_eng]
                                EN
                            )
                        .'</span>
                    </p>
                    <p style="margin: 0px 0; line-height: 1.2;font-size:16px; text-align:right;">
                        <span style="font-weight: bold;">10:00 - 17:00</span>
                    </p>
                </div>
            </div>
        </div>
    </div>';

return $output;
?>