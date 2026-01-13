<?php

class PWEConferenceCalendar { 

    public function __construct() {
        // Hook actions
        add_action('init', array($this, 'init_vc_map_pwe_conference_calendar'));

        add_shortcode('pwe_conference_calendar', array($this, 'pwe_conference_calendar_loop_output'));
    }

    /**
    * Initialize VC Map PWECalendar.
    */
    public function init_vc_map_pwe_conference_calendar() {

        // Check if Visual Composer is available
        if (class_exists('Vc_Manager')) {
            vc_map( array(
                'name' => __( 'PWE Conference Calendar', 'pwe_conference_calendar'),
                'base' => 'pwe_conference_calendar',
                'category' => __( 'PWE Elements', 'pwe_conference_calendar'),
                'admin_enqueue_css' => plugin_dir_url(dirname( __DIR__ )) . 'backend/backendstyle.css',
                
            ));
        }
    }

    public static function get_database_conferences_data() {
        $cap_db = PWECommonFunctions::connect_database();

        if (!$cap_db) {
            if (current_user_can('administrator') && !is_admin()) {
                echo '<script>console.error("Brak połączenia z bazą danych.")</script>';
            }
            return [];
        }

        $conferences = $cap_db->get_results("
            SELECT conf_date_range, conf_name_pl, conf_name_en, conf_slug, conf_img_pl, conf_img_en, conf_site_link, deleted_at
            FROM conferences
        ");
        if ($cap_db->last_error) {
            if (current_user_can("administrator") && !is_admin()) {
                echo '<script>console.error("Błąd SQL (conferences): ' . addslashes($cap_db->last_error) . '")</script>';
            }
            return [];
        }

        $fairs = $cap_db->get_results("
            SELECT 
                f.id,
                f.fair_domain,
                f.fair_name_pl,
                f.fair_name_en,
                f.fair_desc_pl,
                f.fair_desc_en,
                f.fair_date_start,
                f.fair_date_end,
                f.fair_edition,
                -- Subquery do pobrania category_pl
                (SELECT data FROM fair_adds WHERE fair_id = f.id AND slug = 'category_pl' LIMIT 1) AS category_pl,
                -- Subquery do pobrania category_en
                (SELECT data FROM fair_adds WHERE fair_id = f.id AND slug = 'category_en' LIMIT 1) AS category_en
            FROM fairs f
        ");
        if ($cap_db->last_error) {
            if (current_user_can("administrator") && !is_admin()) {
                echo '<script>console.error("Błąd SQL (fairs): ' . addslashes($cap_db->last_error) . '")</script>';
            }
            return [];
        }

        $results = [];

        foreach ($conferences as $conf) {

            // Remove everything in inline brackets + parentheses
            $clean_links = preg_replace('/\[[^\]]*\]/', '', $conf->conf_site_link);

            // Divide by decimal point
            $all_domains = array_map('trim', array_filter(explode(',', $clean_links)));

            // List of domains to exclude (lowercase)
            $excluded_domains = ['mr.glasstec.pl', 'patryk.targibiurowe.com'];

            // Filter allowed domains
            $allowed_domains = array_filter($all_domains, function ($domain) use ($excluded_domains) {
                return !in_array(strtolower($domain), $excluded_domains);
            });

            // If no domains were left after filtering - skip this entry
            if (empty($allowed_domains)) {
                continue;
            }

            // Select a random one from the allowed ones
            $random_domain = $allowed_domains[array_rand($allowed_domains)];

            foreach ($fairs as $fair) {
                if (strcasecmp($random_domain, $fair->fair_domain) === 0) {
                    $results[] = (object) [
                        'conf_name_pl' => $conf->conf_name_pl,
                        'conf_name_en' => $conf->conf_name_en,
                        'conf_slug' => $conf->conf_slug,
                        'conf_date_range' => $conf->conf_date_range,
                        'conf_img_pl' => $conf->conf_img_pl,
                        'conf_img_en' => $conf->conf_img_en,
                        'conf_site_link' => implode(',', $all_domains),
                        'conf_fair_domain' => $fair->fair_domain,
                        'conf_fair_name_pl' => $fair->fair_name_pl,
                        'conf_fair_name_en' => $fair->fair_name_en,
                        'conf_fair_desc_pl' => $fair->fair_desc_pl,
                        'conf_fair_desc_en' => $fair->fair_desc_en,
                        'conf_fair_date_start' => $fair->fair_date_start,
                        'conf_fair_date_end' => $fair->fair_date_end,
                        'conf_fair_edition' => $fair->fair_edition,
                        'conf_fair_category_pl' => $fair->category_pl,
                        'conf_fair_category_en' => $fair->category_en,
                        'conf_deleted_at' => $conf->deleted_at,
                    ];
                    break;
                }
            }
        }

        return $results;
    }

    public function fairs_array() {
        $pwe_groups_data = PWECommonFunctions::get_database_groups_data();
        
        $domains = [];
        
        foreach ($pwe_groups_data as $group) {
            if ($group->fair_group == "gr1" || $group->fair_group == "gr2" || $group->fair_group == "gr3" || $group->fair_group == "b2c") {
                $domains[] = $group->fair_domain;
            }
        }
        
        $all_fairs = [];
        
        foreach ($domains as $domain) {
            $all_fairs[] = do_shortcode("[pwe_name_pl domain=\"$domain\"]");
        }
        
        return $all_fairs;
    }


    public function pwe_conference_calendar_loop_output() {

        $output = '
        <style>
            .pwe-conference-calendar__wrapper {
                max-width: 1400px;
                margin: 0 auto;
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 40px 24px;
                margin-top: 36px;
            }
            .pwe-conference-calendar__item {
                border-radius: 20px;
                cursor: pointer;
                transition: .3s ease;
                overflow: hidden;
                // border: 1px solid #1e1e1e;
                box-shadow: 3px 3px 12px #888888;
            } 
            .pwe-conference-calendar__item:hover {
                transform: scale(1.05);
            }
            .pwe-conference-calendar__item a {
                display: flex;
                flex-direction: column;
                width: 100%;
                height: 100%;
            }
            .pwe-conference-calendar__item-image {
                height: 100%;
                aspect-ratio: 1 / 1;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }
            .pwe-conference-calendar__item-footer {
                background: white;
                display: flex;
                gap: 10px;
            }
            .pwe-conference-calendar__item-footer p {
                margin: 0 !important;
                padding: 4px;
                display: flex;
                justify-content: center;
                font-weight: 600;
                // color: white;
                color: black;
                text-transform: uppercase;
                text-align: center;
            }
            .pwe-conference-calendar__item-edition {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 40%;
            }
            .pwe-conference-calendar__item-logo {
                width: 60%;
                text-align: center;
                display: flex;
                justify-content: center;
            }
            .pwe-conference-calendar__item-logo img {
                width: 85%;
                min-height: 80px;
                object-fit: contain;
                display: flex;
                align-items: center;
            }
            @media (max-width: 1200px) {
                .pwe-conference-calendar__wrapper {
                    grid-template-columns: repeat(3, 1fr);
                    gap: 18px;
                }
            }
            @media (max-width: 768px) {
                .pwe-conference-calendar__wrapper {
                    grid-template-columns: repeat(2, 1fr);
                }
            }
            @media (max-width: 500px) {
                .pwe-conference-calendar__item-footer p {
                    font-size: 12px;
                }
                .pwe-conference-calendar__item-logo img {
                    min-height: 50px;
                }
            }
            .pwe-conference-calendar__filter {
                display: flex;
                justify-content: space-between;
                position: relative;
                flex-wrap: wrap;
                max-width: 1400px;
                margin: 0 auto;
                gap: 5px;
            }
            .pwe-conference-calendar__filter div {
                width: 24%;
            }
            .pwe-conference-calendar__item.dont-show,
            .pwe-conference-calendar__item.dont-show-by-category {
                display: none;
            }
            .pwe-conference-calendar__categories-dropdown,
            .pwe-conference-calendar__fairs-dropdown, {
            .pwe-conference-calendar__months-dropdown
                width: 100%;
            }
            .pwe-conference-calendar__filter input {
                margin-top: 0 !important;
                padding: 0.7em 1.5em;
            }
            .pwe-conference-calendar__filter input::placeholder {
                color: white;
            }
            .pwe-conference-calendar__categories-dropdown,
            .pwe-conference-calendar__fairs-dropdown,
            .pwe-conference-calendar__months-dropdown,
            .pwe-conference-calendar__filter input {
                background: #1d1f24;
                font-size: 18px;
                width: 100%;
                border: none;
                color: #fff;
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-radius: 0.5em;
                cursor: pointer;
                text-transform: uppercase;
            }
            .pwe-conference-calendar__categories-dropdown-btn,
            .pwe-conference-calendar__fairs-dropdown-btn,
            .pwe-conference-calendar__months-dropdown-btn {
                background: transparent;
                width: 100%;
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.7em 1.5em;
                text-transform: uppercase;
            }
            .pwe-conference-calendar__categories-dropdown-arrow,
            .pwe-conference-calendar__fairs-dropdown-arrow,
            .pwe-conference-calendar__months-dropdown-arrow {
                border-left: 5px solid transparent;
                border-right: 5px solid transparent;
                border-top: 6px solid #fff;
                transition: transform ease-in-out 0.3s;
            }
            .pwe-conference-calendar__fairs-dropdown-content,
            .pwe-conference-calendar__categories-dropdown-content,
            .pwe-conference-calendar__months-dropdown-content {
                max-height: 250px;
                overflow-y: auto;
            }
            .pwe-conference-calendar__fairs-dropdown-content.menu-open, {
            .pwe-conference-calendar__categories-dropdown-content.menu-open,
            .pwe-conference-calendar__months-dropdown-content.menu-open
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }
            /* Chrome, Safari, Edge */
            .pwe-conference-calendar__fairs-dropdown-content {
                max-height: 250px;
                overflow-y: auto;
            }
            .pwe-conference-calendar__fairs-dropdown-content::-webkit-scrollbar {
                width: 6px;
            }
            .pwe-conference-calendar__fairs-dropdown-content::-webkit-scrollbar-track {
                background: transparent;
            }
            .pwe-conference-calendar__fairs-dropdown-content::-webkit-scrollbar-thumb {
                background-color: rgba(0,0,0,0.2);
                border-radius: 3px;
                border: 1px solid transparent;
            }
            .pwe-conference-calendar__categories-dropdown-content,
            .pwe-conference-calendar__fairs-dropdown-content,
            .pwe-conference-calendar__months-dropdown-content {
                margin: 5px 0px 0px 0px;
                display: flex;
                flex-direction: column;
                flex-wrap: nowrap;
                z-index: 1000;
                // overflow: hidden;
                background: white;
                list-style: none !important;
                position: absolute;
                top: 3.2em;
                width: 24%;
                padding: 0 !important;
                visibility: hidden;
                opacity: 0;
                border-radius: 8px;
                scrollbar-width: thin;
                scrollbar-color: rgba(0,0,0,0.2) transparent;
            }
            .pwe-conference-calendar__categories-dropdown-content li,
            .pwe-conference-calendar__fairs-dropdown-content li,
            .pwe-conference-calendar__months-dropdown-content li {
                cursor: pointer;
                padding: 0px 0px 0px 0px;
                color: white;
                margin: 2px;
                text-align: center;
                background: #2f3238;
                border-radius: 0.4em;
                position: relative;
                left: 100%;
                transition: 0.4s;
                transition-delay: calc(30ms * var(--delay));
                font-size: 17px;
            }
            .dropdown-delay {
                transition: 0.4s;
                transition-delay: calc(30ms * 9);
            }
            .pwe-conference-calendar__categories-dropdown-content li,
            .pwe-conference-calendar__fairs-dropdown-content li,
            .pwe-conference-calendar__months-dropdown-content li {
                text-transform: uppercase;
            }
            .pwe-conference-calendar__categories-dropdown-content.menu-open li,
            .pwe-conference-calendar__fairs-dropdown-content.menu-open li,
            .pwe-conference-calendar__months-dropdown-content.menu-open li {
                left: 0;
            }
            .pwe-conference-calendar__categories-dropdown-content.menu-open,
            .pwe-conference-calendar__fairs-dropdown-content.menu-open,
            .pwe-conference-calendar__months-dropdown-content.menu-open {
                visibility: visible;
                opacity: 1;
            }
            .pwe-conference-calendar__categories-dropdown-arrow.arrow-rotate,
            .pwe-conference-calendar__fairs-dropdown-arrow.arrow-rotate,
            .pwe-conference-calendar__months-dropdown-arrow.arrow-rotate {
                transform: rotate(180deg);
            }
            .pwe-conference-calendar__categories-dropdown-content li:hover,
            .pwe-conference-calendar__fairs-dropdown-content li:hover,
            .pwe-conference-calendar__months-dropdown-content li:hover {
                background: #1d1f24;
            }
            .pwe-conference-calendar__categories-dropdown-content li a,
            .pwe-conference-calendar__fairs-dropdown-content li a,
            .pwe-conference-calendar__months-dropdown-content li a {
                display: block;
                padding: 0.7em 0.5em;
                color: #fff;
                margin: 0.1em 0;
                text-decoration: none;
            }
            @media (max-width:800px) {
                .pwe-conference-calendar__categories-dropdown-content,
                .pwe-conference-calendar__fairs-dropdown-content,
                .pwe-conference-calendar__months-dropdown-content {
                    width: 100%;
                }
                .pwe-conference-calendar__fairs-dropdown-content {
                    top: 3.2em;
                }
                .pwe-conference-calendar__categories-dropdown-content {
                    top: 6em;
                }
                .pwe-conference-calendar__months-dropdown-content {
                    top: 9em;
                }
                .pwe-conference-calendar__categories-dropdown-content li,
                .pwe-conference-calendar__fairs-dropdown-content li,
                .pwe-conference-calendar__months-dropdown-content li {
                    transition-delay: 0.2s;
                    transition-delay: calc(20ms * var(--delay));
                }
                .pwe-conference-calendar__filter div {
                    width: 100%;
                }
            }
            .pwe-conference-calendar__categories-dropdown-content .all-categories,
            .pwe-conference-calendar__fairs-dropdown-content .all-fairs,
            .pwe-conference-calendar__months-dropdown-content .all-months { 
                background-color: #594334;
                font-size: 21px;
            }
        </style>';

        $conferences = self::get_database_conferences_data(); 

        $lang = PWECommonFunctions::lang_pl();

        // First filter conferences - only future or today
        $today = new DateTime('today');
        $conferences = array_filter($conferences, function($conference) use ($today) {
            if (empty($conference->conf_date_range)) return false;

            // Extract the first date from the range
            $parts = explode(' to ', trim($conference->conf_date_range));
            $date_str = trim($parts[0]);

            $date = DateTime::createFromFormat('Y/m/d', $date_str);
            if (!$date) return false;

            // Leave only today's or future dates
            return $date >= $today;
        });

        // Sort by start date (as before)
        usort($conferences, function ($a, $b) {
            $extract_start_date = function($range) {
                if (empty($range)) return null;

                $parts = explode(' to ', trim($range));
                $date_str = trim($parts[0]);
                $date = DateTime::createFromFormat('Y/m/d', $date_str);
                return $date ?: null;
            };

            $date_start_a = $extract_start_date($a->conf_date_range);
            $date_start_b = $extract_start_date($b->conf_date_range);

            // If dates are equal - sort by domain
            if ($date_start_a == $date_start_b) {
                return strcmp($a->conf_fair_domain, $b->conf_fair_domain);
            }

            // Sort ascending by date
            return ($date_start_a < $date_start_b) ? -1 : 1;
        });



        $output .= '
        <div class="pwe-conference-calendar__filter">

            <div class="pwe-conference-calendar__fairs-dropdown">
                <button id="dropdownFairsBtn" class="pwe-conference-calendar__fairs-dropdown-btn" aria-label="menu button" aria-haspopup="menu" aria-expanded="false" aria-controls="dropdownFairsMenu">
                    <span>'. ($lang ? 'Wybierz targi' : 'Select a trade fair') .'</span>
                    <span class="pwe-conference-calendar__fairs-dropdown-arrow"></span>
                </button>
                <ul class="pwe-conference-calendar__fairs-dropdown-content" role="menu" id="dropdownFairsMenu"></ul>
            </div> 

            <div class="pwe-conference-calendar__categories-dropdown">
                <button id="dropdownCategoriesBtn" class="pwe-conference-calendar__categories-dropdown-btn" aria-label="menu button" aria-haspopup="menu" aria-expanded="false" aria-controls="dropdownCategoriesMenu">
                    <span>'. ($lang ? 'Wybierz branżę' : 'Select an industry') .'</span>
                    <span class="pwe-conference-calendar__categories-dropdown-arrow"></span>
                </button>
                <ul class="pwe-conference-calendar__categories-dropdown-content" role="menu" id="dropdownCategoriesMenu"></ul>
            </div>

            <div class="pwe-conference-calendar__months-dropdown">
                <button id="dropdownMonthsBtn" class="pwe-conference-calendar__months-dropdown-btn" aria-label="menu button" aria-haspopup="menu" aria-expanded="false" aria-controls="dropdownMonthsMenu">
                    <span>'. ($lang ? 'Wybierz miesiąc' : 'Select a month') .'</span>
                    <span class="pwe-conference-calendar__months-dropdown-arrow"></span>
                </button>
                <ul class="pwe-conference-calendar__months-dropdown-content" role="menu" id="dropdownMonthsMenu"></ul>
            </div>

            <div class="pwe-conference-calendar__search">
                <input type="text" id="searchInput" placeholder="'. ($lang ? 'Szukaj' : 'Search') .'" />
            </div>

        </div>

        <div id="pweConferenceCalendar" class="pwe-conference-calendar">
            <div class="pwe-conference-calendar__wrapper">';

            $categories = [];
            $raw_categories = [];
            foreach ($conferences as $conference) {
                $raw_categories[] = ($lang ? $conference->conf_fair_category_pl : $conference->conf_fair_category_en);

                foreach ($raw_categories as $category) {
                    $category = trim($category); 
                    if (!empty($category) && !in_array($category, $categories)) {
                        $categories[] = $category;
                    }
                }
            }

            foreach ($conferences as $conference) {

                $domain = $conference->conf_fair_domain;
                $slug = $conference->conf_slug;
                $link = 'https://' . $domain . ($lang ? '/wydarzenia/' : '/en/conferences/') . '?konferencja=' . $slug;
                $img_url = esc_url(($lang ? $conference->conf_img_pl : $conference->conf_img_en));
                $conf_name = $lang ? $conference->conf_name_pl : $conference->conf_name_en;
                $conf_date = $conference->conf_date_range;
                $fair_name = $lang ? $conference->conf_fair_name_pl : $conference->conf_fair_name_en;
                $fair_desc = $lang ? $conference->conf_fair_desc_pl : $conference->conf_fair_desc_en;
                $fair_category = $lang ? $conference->conf_fair_category_pl : $conference->conf_fair_category_en;
                $fair_date_start = $conference->conf_fair_date_start;
                $fair_date_end = $conference->conf_fair_date_end;
                $conf_deleted_at = $conference->conf_deleted_at;

                $edition_first = $lang ? "Premierowa Edycja" : "Premier Edition";
                $edition_text = $lang ? ". edycja" : ". edition";
                $edition_number = $conference->conf_fair_edition;
                $fair_edition = (!is_numeric($edition_number) || $edition_number == 1) ? $edition_first : $edition_number . $edition_text;

                $logo_congress_src = "https://" . $domain . "/doc/kongres-color.webp"; 

                $exclusions = strpos(strtolower($conference->conf_slug), 'panel-trendow') === false && 
                              strpos(strtolower($conference->conf_name_pl), 'panel trendów') === false &&
                              strpos(strtolower($conference->conf_name_en), 'trends panel') === false &&
                              strpos(strtolower($conference->conf_name_pl), 'scena główna') === false &&
                              strpos(strtolower($conference->conf_name_en), 'main stage') === false &&
                              strpos(strtolower($conference->conf_name_pl), 'ceremonia wręczenia') === false &&
                              strpos(strtolower($conference->conf_name_en), 'medal ceremony') === false;

                              
                
                if (!empty($domain) && $exclusions && $conf_deleted_at == NULL) {
                    $output .= '
                    <div 
                        class="pwe-conference-calendar__item" 
                        data-fair-domain="'. $domain .'"
                        data-fair-name="'. $fair_name .'" 
                        data-fair-desc="'. $fair_desc .'" 
                        data-fair-category="'. $fair_category .'" 
                        data-fair-date-start="'. $fair_date_start .'" 
                        data-fair-date-end="'. $fair_date_end .'" 
                        data-conf-name="'. $conf_name .'"
                        data-conf-date="'. $conf_date .'"
                    >
                        <a href="'. $link . '&utm_source=warsawexpo&utm_medium=refferal&utm_campaign=pwekonf" target="_blank">
                        <div class="pwe-conference-calendar__item-image" style="background-image: url(' . $img_url . ')">
                            
                        </div>
                        <div class="pwe-conference-calendar__item-footer">
                            <div class="pwe-conference-calendar__item-edition">
                                <p>'. $fair_edition .'</p>
                            </div>
                            <div class="pwe-conference-calendar__item-logo">
                                <img src="'. $logo_congress_src .'"/>
                            </div>
                        </div>
                        </a>
                    </div>';

                    $all_fairs[] = [
                        'domain' => $domain,
                        'slug' => $slug,
                        'fair_name' => $fair_name,
                        'fair_desc' => $fair_desc
                    ];
                }
            }
            

            $output .= '
            </div>
        </div>
        
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Dropdown elements
            const dropdownBtn = document.getElementById("dropdownFairsBtn");
            const dropdownContent = document.querySelector(".pwe-conference-calendar__fairs-dropdown-content");
            const dropdownCategoriesBtn = document.getElementById("dropdownCategoriesBtn");
            const dropdownCategoriesContent = document.querySelector(".pwe-conference-calendar__categories-dropdown-content");
            const eventItems = Array.from(document.querySelectorAll(".pwe-conference-calendar__item"));
            const dropdownMonthsBtn = document.getElementById("dropdownMonthsBtn");
            const dropdownMonthsContent = document.querySelector(".pwe-conference-calendar__months-dropdown-content");
            
            // Months in Polish and English
            const monthsPL = ["Styczeń","Luty","Marzec","Kwiecień","Maj","Czerwiec","Lipiec","Sierpień","Wrzesień","Październik","Listopad","Grudzień"];
            const monthsEN = ["January","February","March","April","May","June","July","August","September","October","November","December"];

            function getUniqueMonths(filteredEvents = null) {
                const mSet = new Set();
                (filteredEvents || events).forEach(ev => {
                    const start = ev.date_start;
                    if (start) {
                        const m = start.slice(0,7); // np. 2025/09
                        mSet.add(m);
                    }
                });
                return Array.from(mSet).sort();
            }

            // Event structure
            const events = eventItems.map(item => ({
                domain: (item.getAttribute("data-fair-domain") || "").trim(),
                fair_name: (item.getAttribute("data-fair-name") || "").trim(),
                categories: (item.getAttribute("data-fair-category") || "").split(",").map(c => c.trim()),
                date_start: (item.getAttribute("data-fair-date-start") || "").trim(),
                date_end: (item.getAttribute("data-fair-date-end") || "").trim()
            }));

            // --- GENERATING DOMAIN DROP DOWN ---
            function renderDomains() {
                dropdownContent.innerHTML = "";

                // "All"
                const liAll = document.createElement("li");
                liAll.classList.add("all-fairs");
                liAll.innerText = "'. ($lang ? 'WSZYSTKIE' : 'ALL') .'";
                liAll.style.setProperty("--delay", "1");
                liAll.addEventListener("click", () => {
                    selectedDomain = null;
                    dropdownBtn.innerHTML = `<span>' . ($lang ? 'Wszystkie' : 'All') . '</span><span class="arrow"></span>`;
                    dropdownBtn.setAttribute("aria-expanded", "false");
                    dropdownContent.classList.remove("menu-open");
                    renderCategories();
                    filterEvents();
                });
                dropdownContent.appendChild(liAll);

                // Filter events to display in domains
                let filteredEvents = events;
                if (selectedCategory) {
                    filteredEvents = events.filter(ev => ev.categories.includes(selectedCategory));
                }
                if (selectedMonth) {
                    filteredEvents = filteredEvents.filter(ev => ev.date_start.slice(0, 7) === selectedMonth);
                }
                // Unique domains + fair_name
                const domainMap = new Map();
                filteredEvents.forEach(ev => {
                    if (!domainMap.has(ev.domain)) {
                        domainMap.set(ev.domain, ev.fair_name);
                    }
                });
                // Sort
                const domainList = Array.from(domainMap.entries()).sort((a, b) => a[1].localeCompare(b[1]));

                domainList.forEach(([domain, fair_name], i) => {
                    const li = document.createElement("li");
                    const slug = domain.toLowerCase().replace(/\s+/g, "-");
                    li.classList.add(slug);
                    li.innerText = fair_name.toUpperCase();
                    li.style.setProperty("--delay", (i+2).toString());

                    li.addEventListener("click", () => {
                        selectedDomain = domain;
                        dropdownBtn.innerHTML = `<span>${fair_name}</span><span class="arrow"></span>`;
                        dropdownBtn.setAttribute("aria-expanded", "false");
                        dropdownContent.classList.remove("menu-open");
                        renderCategories();
                        filterEvents();
                    });

                    dropdownContent.appendChild(li);
                });
            }

            // --- GENERATE CATEGORY DROPDOWN ---
            function renderCategories() {
                dropdownCategoriesContent.innerHTML = "";

                // "All"
                const liAllCat = document.createElement("li");
                liAllCat.classList.add("all-categories");
                liAllCat.innerText = "'. ($lang ? 'WSZYSTKIE' : 'ALL') .'";
                liAllCat.style.setProperty("--delay", "1");
                liAllCat.addEventListener("click", () => {
                    selectedCategory = null;
                    dropdownCategoriesBtn.innerHTML = `<span>' . ($lang ? 'Wszystkie' : 'All') . '</span><span class="arrow"></span>`;
                    dropdownCategoriesBtn.setAttribute("aria-expanded", "false");
                    dropdownCategoriesContent.classList.remove("menu-open");
                    renderDomains();
                    filterEvents();
                });
                dropdownCategoriesContent.appendChild(liAllCat);

                // Filter events to display in categories
                let filteredEvents = events;
                if (selectedDomain) {
                    filteredEvents = events.filter(ev => ev.domain === selectedDomain);
                }
                if (selectedMonth) {
                    filteredEvents = filteredEvents.filter(ev => ev.date_start.slice(0, 7) === selectedMonth);
                }
                // Collect unique, individual categories
                const catSet = new Set();
                filteredEvents.forEach(ev => ev.categories.forEach(cat => { if (cat) catSet.add(cat); }));
                const categoriesArr = Array.from(catSet).sort((a, b) => a.localeCompare(b));

                categoriesArr.forEach((category, i) => {
                    const catSlug = category.toLowerCase().replace(/\s+/g, "-");
                    const li = document.createElement("li");
                    li.classList.add(catSlug);
                    li.innerText = category;
                    li.style.setProperty("--delay", (i+2).toString());

                    li.addEventListener("click", () => {
                        selectedCategory = category;
                        dropdownCategoriesBtn.innerHTML = `<span>${category}</span><span class="arrow"></span>`;
                        dropdownCategoriesBtn.setAttribute("aria-expanded", "false");
                        dropdownCategoriesContent.classList.remove("menu-open");
                        renderDomains();
                        filterEvents();
                    });

                    dropdownCategoriesContent.appendChild(li);
                });
            }

            // --- GENERATE MONTHS DROPDOWN ---
            function renderMonths() {
                const dropdownMonthsContent = document.querySelector(".pwe-conference-calendar__months-dropdown-content");
                dropdownMonthsContent.innerHTML = "";

                // "All"
                const liAllMonths = document.createElement("li");
                liAllMonths.classList.add("all-months");
                liAllMonths.innerText = '. ($lang ? '"WSZYSTKIE"' : '"ALL"') .';
                liAllMonths.style.setProperty("--delay", "1");
                liAllMonths.addEventListener("click", () => {
                    selectedMonth = null;
                    dropdownMonthsBtn.innerHTML = `<span>'. ($lang ? 'Wszystkie' : 'All') .'</span><span class="arrow"></span>`;
                    dropdownMonthsBtn.setAttribute("aria-expanded", "false");
                    dropdownMonthsContent.classList.remove("menu-open");
                    filterEvents();
                });
                dropdownMonthsContent.appendChild(liAllMonths);

                // Filtruj eventy po wybranej domenie i kategorii (jeśli wybrane)
                let filteredEvents = events;
                if (selectedDomain) filteredEvents = filteredEvents.filter(ev => ev.domain === selectedDomain);
                if (selectedCategory) filteredEvents = filteredEvents.filter(ev => ev.categories.includes(selectedCategory));

                // Collect unique months
                const monthsArr = getUniqueMonths(filteredEvents);

                monthsArr.forEach((ym, i) => {
                    const [year, month] = ym.split("/");
                    const monthNum = parseInt(month, 10) - 1; // 0-index
                    const monthName = '. ($lang ? 'monthsPL' : 'monthsEN') .'[monthNum];
                    const label = monthName + " " + year;

                    const li = document.createElement("li");
                    li.classList.add("month-"+ym.replace("/", "-"));
                    li.innerText = label;
                    li.style.setProperty("--delay", (i+2).toString());

                    li.addEventListener("click", () => {
                        selectedMonth = ym;
                        dropdownMonthsBtn.innerHTML = `<span>${label}</span><span class="arrow"></span>`;
                        dropdownMonthsBtn.setAttribute("aria-expanded", "false");
                        dropdownMonthsContent.classList.remove("menu-open");
                        filterEvents();
                    });
                    dropdownMonthsContent.appendChild(li);
                });
            }

            // --- OPENING DROPDOWNS SUPPORT ---
            dropdownBtn.addEventListener("click", () => {
                const isOpen = dropdownBtn.getAttribute("aria-expanded") === "true";
                dropdownBtn.setAttribute("aria-expanded", String(!isOpen));
                dropdownContent.classList.toggle("menu-open", !isOpen);

                // CLOSE the second dropdown if open
                dropdownCategoriesBtn.setAttribute("aria-expanded", "false");
                dropdownCategoriesContent.classList.remove("menu-open");
                dropdownMonthsBtn.setAttribute("aria-expanded", "false");
                dropdownMonthsContent.classList.remove("menu-open");
            });

            dropdownCategoriesBtn.addEventListener("click", () => {
                const isOpen = dropdownCategoriesBtn.getAttribute("aria-expanded") === "true";
                dropdownCategoriesBtn.setAttribute("aria-expanded", String(!isOpen));
                dropdownCategoriesContent.classList.toggle("menu-open", !isOpen);

                // CLOSE the second dropdown if open
                dropdownBtn.setAttribute("aria-expanded", "false");
                dropdownContent.classList.remove("menu-open");
                dropdownMonthsBtn.setAttribute("aria-expanded", "false");
                dropdownMonthsContent.classList.remove("menu-open");
            });

            dropdownMonthsBtn.addEventListener("click", () => {
                const isOpen = dropdownMonthsBtn.getAttribute("aria-expanded") === "true";
                dropdownMonthsBtn.setAttribute("aria-expanded", String(!isOpen));
                dropdownMonthsContent.classList.toggle("menu-open", !isOpen);
                // Zamknij pozostałe
                dropdownBtn.setAttribute("aria-expanded", "false");
                dropdownContent.classList.remove("menu-open");
                dropdownCategoriesBtn.setAttribute("aria-expanded", "false");
                dropdownCategoriesContent.classList.remove("menu-open");
            });


            // --- SEARCH ENGINE ---
            const inputSearchElement = document.getElementById("searchInput");
            inputSearchElement?.addEventListener("input", () => {
                const query = inputSearchElement.value.toLowerCase().trim();
                eventItems.forEach(eventItem => {
                    const fairName = eventItem.getAttribute("data-fair-name")?.toLowerCase().trim() || "";
                    const fairDesc = eventItem.getAttribute("data-fair-desc")?.toLowerCase().trim() || "";
                    const confName = eventItem.getAttribute("data-conf-name")?.toLowerCase().trim() || "";
                    const match = fairName.includes(query) || fairDesc.includes(query) || confName.includes(query);

                    // Add AND logic with filters:
                    let show = match;
                    const domain = (eventItem.getAttribute("data-fair-domain") || "").trim();
                    const categories = (eventItem.getAttribute("data-fair-category") || "").split(",").map(c => c.trim());
                    if (selectedDomain && domain !== selectedDomain) show = false;
                    if (selectedCategory && !categories.includes(selectedCategory)) show = false;
                    eventItem.style.display = show ? "" : "none";
                });
            });

            // Active filters
            let selectedDomain = null;
            let selectedCategory = null;
            let selectedMonth = null;

            // --- EVENT FILTERING ---
            function filterEvents() {
                eventItems.forEach(item => {
                    const domain = (item.getAttribute("data-fair-domain") || "").trim();
                    const categories = (item.getAttribute("data-fair-category") || "").split(",").map(c => c.trim());
                    const dateStart = (item.getAttribute("data-fair-date-start") || "").trim();

                    let show = true;
                    if (selectedDomain && domain !== selectedDomain) show = false;
                    if (selectedCategory && !categories.includes(selectedCategory)) show = false;
                    if (selectedMonth && (!dateStart || dateStart.slice(0,7) !== selectedMonth)) show = false;

                    item.style.display = show ? "" : "none";
                });

                renderDomains();
                renderCategories();
                renderMonths();
            }

            // --- INITIALIZATION ---
            renderDomains();
            renderCategories();
            renderMonths();
            filterEvents();
        });
        </script>';

        $output = do_shortcode($output);

        return '<div id="pweConferenceCalendar" class="pwe-conference-calendar">' . $output . '</div>';
    }
}