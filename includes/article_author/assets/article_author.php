<?php

class PWEArticleAuthor extends PWElements {

    public static function initElements() {
        return array(
            array(
                'type' => 'dropdown',
                'heading' => __('Article author', 'pwelement'),
                'param_name' => 'pwe_article_author',
                'value' => array(
                    __('Nikodem Zygadło', 'pwelement')     => 'Nikodem Zygadło',
                    __('Karolina Majewska', 'pwelement')   => 'Karolina Majewska',
                    __('Michał Rutkowski', 'pwelement')    => 'Michał Rutkowski',
                    __('Natalia Stępień', 'pwelement')     => 'Natalia Stępień',
                    __('Paweł Zieliński', 'pwelement')     => 'Paweł Zieliński',
                    __('Aleksandra Krawczyk', 'pwelement') => 'Aleksandra Krawczyk',
                    __('Mariusz Kwiatkowski', 'pwelement') => 'Mariusz Kwiatkowski',
                ),
                'save_always' => true,
                'std' => 'Karolina Majewska',
            ),
        );
    }

    public static function output($atts, $content = null) {
        $atts = shortcode_atts(array(
            'pwe_article_author' => 'Karolina Majewska',
        ), $atts);

        $author_name = sanitize_text_field($atts['pwe_article_author']);

        $authors = array(
            'Karolina Majewska' => array(
                'position' => self::languageChecker(
                    'Analityk rynku i rozwoju wydarzeń branżowych | Dział Analiz Ptak Warsaw Expo',
                    'Market and Industry Events Development Analyst | Analysis Department Ptak Warsaw Expo'
                ),
                'desc' => self::languageChecker(
                    'Na co dzień zajmuje się obserwacją trendów rynkowych oraz analizą sektorów rozwijanych w ramach portfolio targowego Ptak Warsaw Expo.',
                    'On a daily basis, she is engaged in observing market trends and analyzing sectors developed within the trade fair portfolio of Ptak Warsaw Expo.'
                ),
                'image' => '/wp-content/plugins/pwe-media/media/article-authors/karolina-majewska.webp',
            ),
            'Nikodem Zygadło' => array(
                'position' => self::languageChecker(
                    'Dyrektor Działu Analiz | Z-ca Dyrektora ds. Rozwoju | Ptak Warsaw Expo',
                    'Director of Analysis Department | Deputy Director of Development | Ptak Warsaw Expo'
                ),
                'desc' => self::languageChecker(
                    'Pasjonat targów, z niemal 30-letnim doświadczeniem w tworzeniu, organizacji imprez i zarządzaniu projektami targowymi.',
                    'A trade fair enthusiast with nearly 30 years of experience in creating, organizing events and managing trade fair projects.'
                ),
                'image' => '/wp-content/plugins/pwe-media/media/article-authors/nikodem-zygadlo.webp',
            ),
            'Michał Rutkowski' => array(
                'position' => self::languageChecker(
                    'Specjalista ds. analiz branżowych i komunikacji rynkowej | Ptak Warsaw Expo',
                    'Specialist for industry analyses and market communication | Ptak Warsaw Expo'
                ),
                'desc' => self::languageChecker(
                    'Zajmuje się analizą trendów, monitorowaniem zmian zachodzących w poszczególnych sektorach oraz opracowywaniem komunikacji rynkowej wspierającej rozwój wydarzeń targowych organizowanych przez Ptak Warsaw Expo.',
                    'She is engaged in the analysis of trends, monitoring changes taking place in individual sectors, and developing market communication supporting the development of trade fair events organized by Ptak Warsaw Expo.'
                ),
                'image' => '/wp-content/plugins/pwe-media/media/article-authors/michal-rutkowski.webp',
            ),
            'Natalia Stępień' => array(
                'position' => self::languageChecker(
                    'Specjalista ds. analiz branżowych i rozwoju portfolio targowego | Ptak Warsaw Expo',
                    'Specialist for industry analyses and trade fair portfolio development | Ptak Warsaw Expo'
                ),
                'desc' => self::languageChecker(
                    'Odpowiada za monitorowanie zmian rynkowych, analizę potencjału poszczególnych sektorów oraz wsparcie w rozwoju wydarzeń targowych odpowiadających na aktualne potrzeby branży.',
                    'She is responsible for monitoring market changes, analyzing the potential of individual sectors, and supporting the development of trade fair events that respond to the current needs of the industry.'
                ),
                'image' => '/wp-content/plugins/pwe-media/media/article-authors/natalia-stepien.webp',
            ),
            'Paweł Zieliński' => array(
                'position' => self::languageChecker(
                    'Redaktor treści branżowych | Ptak Warsaw Expo',
                    'Industry Content Editor | Ptak Warsaw Expo'
                ),
                'desc' => self::languageChecker(
                    'Tworzy i redaguje materiały dotyczące kluczowych trendów oraz zmian zachodzących w poszczególnych sektorach rynku, wspierając komunikację branżową wydarzeń organizowanych przez Ptak Warsaw Expo.',
                    'She creates and edits materials concerning key trends and changes taking place in individual market sectors, supporting the industry communication of events organized by Ptak Warsaw Expo.'
                ),
                'image' => '/wp-content/plugins/pwe-media/media/article-authors/pawel-zielinski.webp',
            ),
            'Aleksandra Krawczyk' => array(
                'position' => self::languageChecker(
                    'Koordynator komunikacji branżowej | Ptak Warsaw Expo',
                    'Industry Communication Coordinator | Ptak Warsaw Expo'
                ),
                'desc' => self::languageChecker(
                    'Zajmuje się tworzeniem i rozwijaniem komunikacji wokół sektorów obecnych w portfolio targowym, analizując trendy oraz potrzeby rynku i wystawców.',
                    'She is engaged in creating and developing communication around the sectors present in the trade fair portfolio, analyzing trends as well as the needs of the market and exhibitors.'
                ),
                'image' => '/wp-content/plugins/pwe-media/media/article-authors/aleksandra-krawczyk.webp',
            ),
            'Mariusz Kwiatkowski' => array(
                'position' => self::languageChecker(
                    'Specjalista ds. analiz branżowych w Dziale Rozwoju Targów | Ptak Warsaw Expo',
                    'Specialist for industry analyses in the Trade Fair Development Department | Ptak Warsaw Expo'
                ),
                'desc' => self::languageChecker(
                    'Na co dzień zajmuje się analizą trendów rynkowych, obserwacją zmian zachodzących w poszczególnych sektorach oraz wsparciem merytorycznym w rozwoju wydarzeń targowych organizowanych przez Ptak Warsaw Expo.',
                    'On a daily basis, she is engaged in the analysis of market trends, the observation of changes taking place in individual sectors, and substantive support in the development of trade fair events organized by Ptak Warsaw Expo.'
                ),
                'image' => '/wp-content/plugins/pwe-media/media/article-authors/mariusz-kwiatkowski.webp',
            ),
        );

        if (empty($author_name) || !isset($authors[$author_name])) {
            return '';
        }

        $author_position = $authors[$author_name]['position'];
        $author_desc     = $authors[$author_name]['desc'];
        $author_image    = $authors[$author_name]['image'];
        

        return '
            <div class="pwe-news-article_author__linkedin">
                <div class="pwe-news-article_author__linkedin-content">
                    <img src="' . esc_url($author_image) . '" alt="' . esc_attr($author_name) . '">
                    <div class="pwe-news-article_author__linkedin-content-text">
                        <h2 class="pwe-news-article_author__linkedin-title">' . esc_html($author_name) . '</h2>
                        <h3 class="pwe-news-article_author__linkedin-subtitle">' . esc_html($author_position) . '</h3>
                        <p class="pwe-news-article_author__linkedin-desc">' . esc_html($author_desc) . '</p>
                    </div>
                </div>
                    <div class="pwe-news-article_author__linkedin-footer">
                        <p class="pwe-news-article_author__linkedin-thx">' . self::languageChecker('Dziękujemy, że przeczytałaś/eś nasz artykuł do końca.', 'Thank you for reading our article to the end.') . '</p>
                        <a class="pwe-news-article_author__btn--black" target="_blank" href="https://www.linkedin.com/build-relation/newsletter-follow?entityUrn=7185929412658302977">' . self::languageChecker('Dołącz do Newslettera na LinkedIn', 'Join the newsletter on LinkedIn') . '</a>
                    </div>
            </div>';
    }
}