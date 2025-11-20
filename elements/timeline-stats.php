<?php

/**
 * Class PWElementMedals
 * Extends PWElements class and defines a pwe Visual Composer element.
 */
class PWElementTimelineStats extends PWElements {

    /**
     * Constructor method.
     * Calls parent constructor and adds an action for initializing the Visual Composer map.
     */
    public function __construct() {
        parent::__construct();
    }

        public static function initElements() {
        $element_output = array(
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Heading Timeline', 'pwe_timeline_stats'),
                'param_name' => 'pwe_timeline_stats_heading',
                'save_always' => true,
                'admin_label' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTimelineStats',
                ),
            ),
            array(
                'type' => 'textarea',
                'group' => 'PWE Element',
                'heading' => __('Text Timeline', 'pwe_timeline_stats'),
                'param_name' => 'pwe_timeline_stats_text',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTimelineStats',
                ),
            ),
            array(
                'type' => 'attach_image',
                'group' => 'PWE Element',
                'heading' => __('Timeline Background', 'pwe_timeline_stats'),
                'param_name' => 'pwe_timeline_stats_background',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTimelineStats',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Timeline Background link', 'pwe_timeline_stats'),
                'param_name' => 'pwe_timeline_stats_background_link',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTimelineStats',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Btn Text Timeline', 'pwe_timeline_stats'),
                'param_name' => 'pwe_timeline_stats_btn_text',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTimelineStats',
                ),
            ),
            array(
                'type' => 'textfield',
                'group' => 'PWE Element',
                'heading' => __('Btn Link Timeline', 'pwe_timeline_stats'),
                'param_name' => 'pwe_timeline_stats_btn_link',
                'param_holder_class' => 'backend-area-half-width',
                'save_always' => true,
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTimelineStats',
                ),
            ),
            array(
                'type' => 'dropdown',
                'group' => 'PWE Element',
                'heading' => __('Timeline SVG Style', 'pwe_timeline_stats'),
                'param_name' => 'pwe_timeline_stats_svg_style',
                'save_always' => true,
                'value' => array(
                    'Smooth' => 'pwe_timeline_stats_svg_smooth',
                    'Zigzag' => 'pwe_timeline_stats_svg_zigzag',
                ),
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTimelineStats',
                ),
            ),
            array(
                'type' => 'param_group',
                'group' => 'PWE Element',
                'heading' => __('Events', 'pwe_timeline_stats'),
                'param_name' => 'pwe_timeline_stats_events',
                'dependency' => array(
                    'element' => 'pwe_element',
                    'value' => 'PWElementTimelineStats',
                ),
                'params' => array(
                    array(
                        'type' => 'attach_image',
                        'heading' => __('Event Image', 'pwe_timeline_stats'),
                        'param_name' => 'pwe_timeline_stats_event_img',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Event Image URL', 'pwe_timeline_stats'),
                        'param_name' => 'pwe_timeline_stats_event_img_url',
                        'description' => __('Optional. If provided, it will override the uploaded image.'),
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Event Year', 'pwe_timeline_stats'),
                        'param_name' => 'pwe_timeline_stats_event_year',
                        'param_holder_class' => 'backend-area-half-width',
                        'save_always' => true,
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Event Edition', 'pwe_timeline_stats'),
                        'param_name' => 'pwe_timeline_stats_event_edition',
                        'param_holder_class' => 'backend-area-half-width',
                        'save_always' => true,
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Event Exhibitors', 'pwe_timeline_stats'),
                        'param_name' => 'pwe_timeline_stats_event_exhibitors',
                        'param_holder_class' => 'backend-area-one-third-width thumbnails_width_columns',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Event Visitors', 'pwe_timeline_stats'),
                        'param_name' => 'pwe_timeline_stats_event_visitors',
                        'param_holder_class' => 'backend-area-one-third-width thumbnails_width_columns',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Event Area', 'pwe_timeline_stats'),
                        'param_name' => 'pwe_timeline_stats_event_area',
                        'param_holder_class' => 'backend-area-one-third-width thumbnails_width_columns',
                        'save_always' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Other Event', 'pwe_timeline_stats'),
                        'param_name' => 'pwe_timeline_stats_other_events',
                        'save_always' => true,
                    ),
                ),
            ), 
        );
        return $element_output;
    }

    public static function output($atts) {
        $btn_text_color = self::findColor($atts['btn_text_color_manual_hidden'], $atts['btn_text_color'], 'white') . '!important';
        $btn_color = self::findColor($atts['btn_color_manual_hidden'], $atts['btn_color'], self::$accent_color) . '!important';
        $btn_border = self::findColor($atts['text_color_manual_hidden'], $atts['text_color'], self::$accent_color) . '!important';

        extract(shortcode_atts(array(
            'pwe_timeline_stats_heading' => '',
            'pwe_timeline_stats_text' => '',
            'pwe_timeline_stats_btn_text' => '',
            'pwe_timeline_stats_btn_link' => '',
            'pwe_timeline_stats_svg_style' => 'pwe_timeline_stats_svg_smooth',
            'pwe_timeline_stats_background' => '',
            'pwe_timeline_stats_background_link' => '',
        ), $atts));

        $darker_btn_color = self::adjustBrightness($btn_color, -20);

        $event_items = vc_param_group_parse_atts($atts['pwe_timeline_stats_events']);

        $img_data = wp_get_attachment_image_src($pwe_timeline_stats_background, 'full');
        $timeline_background = $img_data[0] ?? $pwe_timeline_stats_background_link ?? '';

        $cta_html = '
            <div class="timeline-pwe-event timeline-pwe-cta timeline-pwe-event--top">
                <div class="timeline-pwe-card" style="justify-content: center; align-items: center; text-align: center;">
                    <div style="padding: 1rem 1rem 1rem 0; margin-right: auto;">
                        <h2 style="font-size: 1.75rem; margin-bottom: 1rem;">' . esc_html($pwe_timeline_stats_heading) . '</h2>
                        <p style="font-size: 1rem; max-width: 300px; margin: auto;">' . esc_html($pwe_timeline_stats_text) . '</p>';

        if (!empty($pwe_timeline_stats_btn_text) && !empty($pwe_timeline_stats_btn_link)) {
            $cta_html .= '<a href="' . esc_url($pwe_timeline_stats_btn_link) . '" class="timeline-pwe-cta-btn pwe-button-link" style="margin-top: 1rem;">'
                    . esc_html($pwe_timeline_stats_btn_text) . '</a>';
        }

        $cta_html .= '
                    </div>
                </div>
            </div>';


        $events_html = '';

        foreach ($event_items as $idx => $event) {
            $img = !empty($event['pwe_timeline_stats_event_img_url'])
                ? esc_url($event['pwe_timeline_stats_event_img_url'])
                : (wp_get_attachment_image_src($event['pwe_timeline_stats_event_img'], 'full')[0] ?? '');
            $year = esc_html($event['pwe_timeline_stats_event_year'] ?? '');
            $edition = esc_html($event['pwe_timeline_stats_event_edition'] ?? '');
            $exhibitors = esc_html($event['pwe_timeline_stats_event_exhibitors'] ?? '');
            $visitors = number_format((int)$event['pwe_timeline_stats_event_visitors'], 0, ',', ' ');
            $area = number_format((int)$event['pwe_timeline_stats_event_area'], 0, ',', ' ');

            $direction = $idx % 2 === 0 ? 'timeline-pwe-event--down' : 'timeline-pwe-event--top';

            $logos_html = '';
            if (!empty($event['pwe_timeline_stats_other_events'])) {
                $domains = array_map('trim', explode(',', $event['pwe_timeline_stats_other_events']));
                foreach ($domains as $domain) {
                    $logos_html .= '<img src="https://' . esc_attr($domain) . '/doc/logo-color.webp" alt="' . esc_attr($domain) . '" style="height: 100%; aspect-ratio: auto; object-fit: contain; padding: 0.25rem;" />';
                }
            }

            $events_html .= '
                <div class="timeline-pwe-event ' . $direction . '">
                    <span class="timeline-pwe-connector"></span>
                    <div class="timeline-pwe-card">
                        <div class="timeline-pwe-card-top">';
                            if (!empty($img)){
                                $events_html .= '
                                <img src="' . $img . '" alt="' . $year . '" />';
                            }
                            $events_html .= '
                            <div class="timeline-pwe-card-content">
                                <header>
                                    <h3 class="timeline-pwe-year">' . $year . '</h3>
                                    <span class="timeline-pwe-badge">' . $edition . '</span>
                                </header>
                                <dl>
                                    <dt>wystawcy</dt><dd>' . $exhibitors . '</dd>
                                    <dt>odwiedzający</dt><dd>' . $visitors . '</dd>
                                    <dt>powierzchnia</dt><dd>' . $area . ' m²</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="timeline-pwe-card-bottom">
                            ' . $logos_html . '
                        </div>
                    </div>
                </div>';
        }


        $output = '
        <style>
            :root {
                --timeline2-accent-color-light: #e9e5ff;
                --gray-100: #f3f4f6;
                --gray-300: #d1d5db;
                --ink: #0f172a;
                --event-width: 460px;
                --event-gap: 1rem;
            }

            .row.limit-width.row-parent:has(#pwe-timeline-stats) {
                padding: 0 !important;
                max-width: 100% !important;
            }

            .timeline-pwe-wrapper {
                padding: 6rem 0 15rem;
                min-height: 800px;
                overflow-x: auto;
                position: relative;
                cursor: grab;
                user-select: none;
            }

            .timeline-pwe-wrapper.grabbing {
                cursor: grabbing;
            }

            .timeline-pwe-wrapper::-webkit-scrollbar {
                height: 10px;
                width: 800px;
                margin: 0 auto;
            }

            .timeline-pwe-wrapper::-webkit-scrollbar-track {
                background: var(--gray-100);
                border-radius: 5px;
            }

            .timeline-pwe-wrapper::-webkit-scrollbar-thumb {
                background: var(--main2-color);
                border-radius: 5px;
            }

            .timeline-pwe-track {
                position: absolute;
                display: flex;
                gap: var(--event-gap);
                width: max-content;
                top: 50%;
                transform: translateY(-50%);
            }

            .timeline-pwe-svg {
                position: absolute;
                top: 50%;
                left: 0;
                transform: translateY(-50%);
                height: 220px;
                pointer-events: none;
                z-index: 10;
            }

            .timeline-pwe-point-outer { fill: var(--timeline2-accent-color-light); }
            .timeline-pwe-point-inner {
                fill: #fff;
                stroke: var(--accent-color);
                stroke-width: 4;
            }

            .timeline-pwe-event {
                position: relative;
                width: var(--event-width);
                flex: 0 0 auto;
                scroll-snap-align: center;
            }
            .timeline-pwe-event--top  { transform: translateY(var(--offset-up)); }
            .timeline-pwe-event--down { transform: translateY(var(--offset-down)); }

            .timeline-pwe-card {
                background: #fff;
                border-radius: 1rem;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
                overflow: hidden;
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                aspect-ratio: 6 / 3;
            }
            
            .timeline-pwe-card-top {
                display: flex;
                flex-direction: row-reverse;
                height: 76%;
                width: 100%;
            }
            
            .timeline-pwe-card-bottom {
                display: flex;
                flex-direction: row;
                height: 24%;
                width: 100%;
            }
            
            .timeline-pwe-card dl {
                border-radius: 0.5rem;
                overflow: hidden;
            }

            .timeline-pwe-card dl dt,
            .timeline-pwe-card dl dd {
                padding: 0.25rem 0.5rem;
            }
            
            .timeline-pwe-card dl > dt:nth-of-type(1),
            .timeline-pwe-card dl > dd:nth-of-type(1) {
                background-color: #f4f4f4;
            }
            .timeline-pwe-card dl > dt:nth-of-type(3),
            .timeline-pwe-card dl > dd:nth-of-type(3) {
                background-color: #f4f4f4;
            }

            .timeline-pwe-card img {
                width: 36%;
                height: 100%;
                object-fit: cover;
            }
            
            .timeline-pwe-card-bottom img {
                max-width: 12%;
                box-sizing: border-box;
            }

            .timeline-pwe-card-content {
                padding: 12px 12px 2px;
                width: 100%;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }

            .timeline-pwe-card-content header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin: 0;
            }

            .timeline-pwe-year   { font-size: 2rem; font-weight: 800; margin: 0; }
            .timeline-pwe-badge  {
                font-size: 0.75rem;
                background: var(--accent-color);
                color: #fff;
                border-radius: 9999px;
                padding: 0.35rem 1rem;
                white-space: nowrap;
            }

            dl {
                display: grid;
                grid-template-columns: 1fr auto;
                font-size: 1rem;
                margin: 0 !important;
            }

            dt { font-weight: 600; margin: 0 !important; }
            dd { text-align: right; margin: 0; }

            .timeline-pwe-connector {
                position: absolute;
                width: 2px;
                background: var(--gray-300);
                z-index: -2;
            }

            .timeline-pwe-wrapper .timeline-pwe-cta .timeline-pwe-card {
                position: absolute;
                box-shadow: unset;
                text-align: left !important;
                aspect-ratio: unset;
                width: 420px;
                left: 106% !important;
                top: -32px;
                background: unset;
            }
            .timeline-pwe-event.first-regular-event::before {
                content: "Estymacje";
                position: absolute;
                width: 100%;
                height: 100%;
                background: var(--main2-color);
                top: -28px;
                left: 0;
                z-index: -1;
                border-radius: 1rem;
                text-align: center;
                padding: 6px;
                color: white;
                font-weight: 500;
            }
            
            .timeline-pwe-card-mobile .timeline-pwe-cta{
                display: none;
                position: absolute;
                justify-content: center;
                align-items: center;
                padding: 36px;
                text-align: center !important;
                width: 100%;
                left: 0;
                top: 16%;
                transform: translateY(-50%);
            }

            .timeline-pwe-cta-btn {
                display: inline-flex;
                align-items: center;
                padding: 14px 32px;
                background: var(--main2-color);
                color: var(--accent-color) !important;
                font-size: 18px;
                font-weight: 600;
                text-transform: uppercase;
                text-decoration: none;
                border-radius: 36px;
            }

            .timeline-pwe-cta-btn:hover { 
                background-color: color-mix(in srgb, var(--main2-color) 80%, white 20%);
            }
            
            @media(max-width: 570px){
                .timeline-pwe-wrapper .timeline-pwe-cta, .timeline-pwe-connector {
                    display: none;
                }
                .timeline-pwe-card-mobile .timeline-pwe-cta {
                    display: flex;
                }
                .timeline-pwe-card-mobile .timeline-pwe-cta .timeline-pwe-card div {
                    padding: 1rem !important;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    text-align: center;
                }
                .wpb_wrapper:has(#timeline-pwe-wrapper):before {
                    background: url(https://warsawhomekitchen.com/doc/new_home/hale-timeline-mobile.webp);
                }
                .timeline-pwe-track div:first-of-type {
                    min-width: 12px;
                }
                .timeline-pwe-wrapper {
                    min-height: 900px;
                    padding: 0 0 15rem;
                    margin-top: 64px;
                }
                .timeline-pwe-svg {
                    top: 40%;
                }
                .timeline-pwe-track {
                    top: 40%;
                }
                .timeline-pwe-card {
                    aspect-ratio: unset;
                    max-width: 85vw;
                    gap: 8px;
                    justify-content: space-between;
                }
                .timeline-pwe-card-top {
                    display: flex;
                    flex-direction: column;
                    height: 70%;
                }
                .timeline-pwe-card img {
                    width: 100%;
                    height: 100%;
                    max-height: 140px;
                }
                .timeline-pwe-card-content {
                    box-sizing: border-box;
                    gap: 8px;
                }
                .timeline-pwe-card-bottom {
                    padding: 0 12px 12px;
                }
                .timeline-pwe-event.first-regular-event::before {
                    max-width: 85vw;
                }
            }
        </style>';

        if (!empty($timeline_background)) {
            $output .= '
            <style>
            .pwelement:has(#pwe-timeline-stats):before {
                content: "";
                width: 100%;
                height: 100%;
                position: absolute;
                left: 0;
                top: 0;
                background: url(' . esc_url($timeline_background) . ');
                background-size: cover;
                background-repeat: no-repeat;
                opacity: 0.3;
            }
            </style>';
        }

        $output .= '
        <div id="pwe-timeline-stats" class="timeline-stats">
            <div class="timeline-pwe-card-mobile">
                ' . $cta_html . '
            </div>
            <div class="timeline-pwe-wrapper" id="timeline-pwe-wrapper">
                <svg class="timeline-pwe-svg" id="timeline-pwe-svg" xmlns="http://www.w3.org/2000/svg"></svg>
                <div class="timeline-pwe-track" id="timeline-pwe-track">
                    <div style="width: 0; flex-shrink: 0;"></div> <!-- lewy margines -->
                    ' . $cta_html . $events_html . '
                    <div style="width: 10vw; flex-shrink: 0;" id="right-margin"></div> <!-- prawy margines -->
                </div>
            </div>
        </div>

        <script>
            const track = document.getElementById("timeline-pwe-track");
            const svg = document.getElementById("timeline-pwe-svg");
            const wrapper = document.getElementById("timeline-pwe-wrapper");
            const TIMELINE_AMPL = "' . $pwe_timeline_stats_svg_style . '" === "pwe_timeline_stats_svg_zigzag" ? 18 : 0;

            document.addEventListener("DOMContentLoaded", () => {

            // ➕ Po renderze — pozycjonowanie i szerokość CTA
            setTimeout(() => {
            const cta = document.querySelector(".timeline-pwe-wrapper .timeline-pwe-cta");
            const ctaCard = document.querySelector(".timeline-pwe-wrapper .timeline-pwe-cta .timeline-pwe-card");

            function updateCtaWidth() {
                if (!cta) return;

                if (window.innerWidth >= 1200) {
                const width = (window.innerWidth - 1200) / 2;
                cta.style.width = `${width}px`;
                } else {
                cta.style.width = "0"; // pełna szerokość na mobile
                }
            }

            updateCtaWidth();
            window.addEventListener("resize", updateCtaWidth);

            const firstEvent = document.querySelectorAll(".timeline-pwe-event")[1];
            if (firstEvent && ctaCard) {
                ctaCard.style.left = `${firstEvent.offsetLeft}px`;
            }

            const firstRegularEvent = document.querySelector(".timeline-pwe-event:not(.timeline-pwe-cta)");
            if (firstRegularEvent) {
                firstRegularEvent.classList.add("first-regular-event");
            }

            calculateConnectors();
            }, 50);


            // 👉 Reszta funkcji (bez zmian)
            function calculateConnectors() {
                /* ——— obliczenia offsetu kart ——— */
                const sampleCard = track.querySelector(".timeline-pwe-card");
                const cardHeight = sampleCard.offsetHeight;
                const svgHeight  = parseFloat(getComputedStyle(svg).height);
                let offset;

                if (window.innerWidth < 570) {
                    offset = (cardHeight + svgHeight) / 0.9;
                    document.documentElement.style.setProperty("--offset-up",  `${offset}px`);
                    document.documentElement.style.setProperty("--offset-down",`${offset}px`);
                } else {
                    offset = (cardHeight + svgHeight) / 2.4;
                    document.documentElement.style.setProperty("--offset-up",  `-${offset}px`);
                    document.documentElement.style.setProperty("--offset-down",`${offset}px`);
                }

                /* ——— docelowe Y kółek (środek zygzaka) ——— */
                const wrapperRect = wrapper.getBoundingClientRect();
                const midYPage    = wrapperRect.top + wrapperRect.height / 2 + window.scrollY;
                const circleY     = midYPage - TIMELINE_AMPL;   // ⬅️ najważniejsza linijka

                /* ——— rysowanie pionowych łączników ——— */
                document.querySelectorAll(".timeline-pwe-event").forEach(evDiv => {
                    const rect       = evDiv.getBoundingClientRect();
                    const connector  = evDiv.querySelector(".timeline-pwe-connector");
                    if (!connector) return;

                    connector.style.left      = "36px";
                    connector.style.transform = "none";

                    const evTop    = rect.top    + window.scrollY;
                    const evBottom = rect.bottom + window.scrollY;

                    if (evDiv.classList.contains("timeline-pwe-event--top")) {
                    /* karta nad osią – słupek w dół */
                    const needed = circleY - evBottom - 2;
                    connector.style.top    = `${rect.height}px`;
                    connector.style.bottom = "auto";
                    connector.style.height = `${needed}px`;
                    } else {
                    /* karta pod osią – słupek w górę */
                    const needed = evTop - circleY - 2;
                    connector.style.bottom = `${rect.height}px`;
                    connector.style.top    = "auto";
                    connector.style.height = `${needed}px`;
                    }
                });

                drawTimeline();   // pozostaje bez zmian
            }';

            if ($pwe_timeline_stats_svg_style == 'pwe_timeline_stats_svg_smooth') {

                $output .= '
                function drawTimeline() {
                    const midY = 110;
                    const eventDivs = Array.from(track.querySelectorAll(".timeline-pwe-event"))
                        .filter(div => div.querySelector("img"));

                    const xs = eventDivs.map(div => {
                        const rect = div.getBoundingClientRect();
                        const parentRect = track.getBoundingClientRect();
                        return rect.left - parentRect.left + 36;
                    });

                    const svgWidth = Math.max(...xs) + 40;
                    svg.setAttribute("width", svgWidth);
                    svg.setAttribute("height", "220");
                    svg.setAttribute("viewBox", `0 0 ${svgWidth} 220`);

                    let d = `M${xs[0]} ${midY} `;
                    for (let i = 0; i < xs.length - 1; i++) {
                        const x0 = xs[i];
                        const x3 = xs[i + 1];
                        const delta = (x3 - x0) / 3;
                        const x1 = x0 + delta;
                        const x2 = x0 + 2 * delta;
                        const y1 = midY - 40;
                        const y2 = midY + 40;
                        d += `C ${x1} ${y1}, ${x2} ${y2}, ${x3} ${midY} `;
                    }

                    svg.innerHTML = `
                        <defs>
                        <linearGradient id="timelineGradient" gradientUnits="userSpaceOnUse" x1="0" y1="0" x2="0" y2="0">
                            <stop offset="0%"   stop-color="var(--accent-color)" stop-opacity="0" />
                            <stop offset="25%"  stop-color="var(--accent-color)" stop-opacity="1" />
                            <stop offset="75%"  stop-color="var(--accent-color)" stop-opacity="1" />
                            <stop offset="100%" stop-color="var(--accent-color)" stop-opacity="0" />
                        </linearGradient>
                        </defs>
                    `;

                    const bgPath = document.createElementNS("http://www.w3.org/2000/svg", "path");
                    bgPath.setAttribute("d", d);
                    bgPath.setAttribute("fill", "transparent");
                    bgPath.setAttribute("stroke", "#d1d5db");
                    bgPath.setAttribute("stroke-width", "6");
                    bgPath.setAttribute("stroke-linecap", "round");
                    svg.appendChild(bgPath);

                    const fgPath = document.createElementNS("http://www.w3.org/2000/svg", "path");
                    fgPath.setAttribute("d", d);
                    fgPath.setAttribute("fill", "transparent");
                    fgPath.setAttribute("stroke", "url(#timelineGradient)");
                    fgPath.setAttribute("stroke-width", "6");
                    fgPath.setAttribute("stroke-linecap", "round");
                    svg.appendChild(fgPath);

                    xs.forEach(cx => {
                        const outer = document.createElementNS("http://www.w3.org/2000/svg", "circle");
                        outer.setAttribute("class", "timeline-pwe-point-outer");
                        outer.setAttribute("cx", cx);
                        outer.setAttribute("cy", midY);
                        outer.setAttribute("r", 16);
                        svg.appendChild(outer);

                        const inner = document.createElementNS("http://www.w3.org/2000/svg", "circle");
                        inner.setAttribute("class", "timeline-pwe-point-inner");
                        inner.setAttribute("cx", cx);
                        inner.setAttribute("cy", midY);
                        inner.setAttribute("r", 8);
                        svg.appendChild(inner);
                    });

                    const grad = svg.querySelector("#timelineGradient");
                    const firstPointX = xs[0];

                    function updateGradientPosition() {
                        const scrollLeft = wrapper.scrollLeft;
                        const trackLeftInWrapper = track.offsetLeft;
                        const centerInWrapper = scrollLeft + wrapper.clientWidth / 4;
                        const svgCenter = centerInWrapper - trackLeftInWrapper;
                        const range = wrapper.clientWidth * 0.5;
                        const gradientCenter = firstPointX + (svgCenter - firstPointX);
                        const start = gradientCenter - range;
                        const end = gradientCenter + range;

                        grad.setAttribute("x1", start);
                        grad.setAttribute("x2", end);
                    }

                    wrapper.addEventListener("scroll", updateGradientPosition);
                    window.addEventListener("resize", updateGradientPosition);
                    updateGradientPosition();
                }';

            } else if ($pwe_timeline_stats_svg_style == 'pwe_timeline_stats_svg_zigzag') { 

                $output .= '
                function drawTimeline() {
                    const midY = 100;
                    const eventDivs = Array.from(track.querySelectorAll(".timeline-pwe-event"))
                    .filter(div => div.querySelector("img"));

                    const xs = eventDivs.map(div => {
                    const rect = div.getBoundingClientRect();
                    const parentRect = track.getBoundingClientRect();
                    return rect.left - parentRect.left + 36;
                    });

                    const svgWidth = Math.max(...xs) + 40;
                    svg.setAttribute("width", svgWidth);
                    svg.setAttribute("height", "220");
                    svg.setAttribute("viewBox", `0 0 ${svgWidth} 220`);

                    let d = `M${xs[0]} ${midY - 20} `;
                    const segmentCount = 6;
                    const amplitude = 18;

                    for (let i = 0; i < xs.length - 1; i++) {
                    const xStart = xs[i];
                    const xEnd = xs[i + 1];
                    const segmentWidth = (xEnd - xStart) / segmentCount;

                    for (let j = 1; j <= segmentCount; j++) {
                        const x = xStart + j * segmentWidth;
                        const y = j % 2 === 0 ? midY - amplitude : midY + amplitude;
                        d += `L${x} ${y} `;
                    }
                    }

                    const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
                    path.setAttribute("d", d);
                    path.setAttribute("fill", "transparent");
                    path.setAttribute("stroke", "var(--accent-color)");
                    path.setAttribute("stroke-width", "4");
                    path.setAttribute("stroke-linecap", "round");
                    path.setAttribute("stroke-dasharray", "14,14");
                    svg.appendChild(path);

                    xs.forEach(cx => {
                    const outer = document.createElementNS("http://www.w3.org/2000/svg", "circle");
                    outer.setAttribute("class", "timeline-pwe-point-outer");
                    outer.setAttribute("cx", cx);
                    outer.setAttribute("cy", midY - amplitude);
                    outer.setAttribute("r", 16);
                    svg.appendChild(outer);

                    const inner = document.createElementNS("http://www.w3.org/2000/svg", "circle");
                    inner.setAttribute("class", "timeline-pwe-point-inner");
                    inner.setAttribute("cx", cx);
                    inner.setAttribute("cy", midY - amplitude);
                    inner.setAttribute("r", 8);
                    svg.appendChild(inner);
                    });
                }';
            }

            $output .= '
            (function enableFastGrabScroll() {
                let isDragging = false;
                let startX = 0;
                let scrollStart = 0;

                wrapper.addEventListener("mousedown", (e) => {
                    isDragging = true;
                    startX = e.pageX;
                    scrollStart = wrapper.scrollLeft;
                    wrapper.classList.add("grabbing");
                    e.preventDefault();
                });

                document.addEventListener("mousemove", (e) => {
                    if (!isDragging) return;
                    const deltaX = e.pageX - startX;
                    wrapper.scrollLeft = scrollStart - deltaX;
                });

                document.addEventListener("mouseup", () => {
                    isDragging = false;
                    wrapper.classList.remove("grabbing");
                });

                wrapper.addEventListener("touchstart", (e) => {
                    startX = e.touches[0].pageX;
                    scrollStart = wrapper.scrollLeft;
                }, { passive: true });

                wrapper.addEventListener("touchmove", (e) => {
                    const deltaX = e.touches[0].pageX - startX;
                    wrapper.scrollLeft = scrollStart - deltaX;
                }, { passive: true });
                })();
            });

        </script>';

        return $output;
    }
}