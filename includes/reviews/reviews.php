<?php

/**
 * Class PWEReviews
 * Extends maps class and defines a custom Visual Composer element for vouchers.
 */
class PWEReviews extends PWECommonFunctions {

    /**
     * Constructor method for initializing the plugin.
     */
    public function __construct() {
        // Hook actions
        add_action('init', array($this, 'initVCMapPWEReviews'));
        add_shortcode('pwe_reviews', array($this, 'PWEReviewsOutput'));
    }

    /**
     * Initialize VC Map PWEReviews.
     */
    public function initVCMapPWEReviews() {

        // Check if Visual Composer is available
        if (class_exists('Vc_Manager')) {
            vc_map( array(
                'name' => __( 'PWE Reviews', 'pwe_reviews'),
                'base' => 'pwe_reviews',
                'category' => __( 'PWE Elements', 'pwe_reviews'),
                'admin_enqueue_css' => plugin_dir_url(dirname( __DIR__ )) . 'backend/backendstyle.css',
            ));
        }
    }

    /**
     * Static method to generate the HTML output for the PWE Element.
     * Returns the HTML output as a string.
     *
     * @param array @atts options
     */
    public function PWEReviewsOutput($atts) {

        // Twój klucz API Google
        $apiKey = 'AIzaSyAEFeeTT7BxfdLDOEhOuIVjPiyerIelLEQ';

        // PLACE_ID miejsca, którego opinie chcesz pobrać
        $placeId = 'ChIJbYfYrBo3GUcRD48ve6pZU1g';  // Zmień na odpowiedni PLACE_ID

        // Funkcja do wykonania zapytania do API
        function getPlaceDetails($placeId, $apiKey) {
            $url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=$placeId&key=$apiKey";
            $response = file_get_contents($url);
            return json_decode($response, true);
        }

        // Funkcja do pobrania opinii z wyników
        function getReviews($placeId, $apiKey) {
            $allReviews = [];
            $nextPageToken = null;

            do {
                // Jeśli istnieje next_page_token, dodaj go do URL
                $url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=$placeId&key=$apiKey";
                if ($nextPageToken) {
                    $url .= "&pagetoken=$nextPageToken";
                }

                // Wykonaj zapytanie
                $response = file_get_contents($url);
                $data = json_decode($response, true);

                // Sprawdź, czy są opinie
                if (isset($data['result']['reviews'])) {
                    $allReviews = array_merge($allReviews, $data['result']['reviews']);
                }

                // Sprawdź, czy istnieje kolejna strona z opiniami (next_page_token)
                $nextPageToken = isset($data['next_page_token']) ? $data['next_page_token'] : null;

                // Opcjonalnie dodaj opóźnienie między zapytaniami, aby Google API mogło wygenerować kolejny token
                if ($nextPageToken) {
                    sleep(5);  // Czekaj 2 sekundy przed kolejnym zapytaniem
                }

                var_dump($nextPageToken);

            } while ($nextPageToken);

            return $allReviews;
        }

        // Pobierz opinie
        $reviews = getReviews($placeId, $apiKey);

        // var_dump($reviews);

        // Wyświetl opinie
        // echo "<h2>Opinie dla miejsca:</h2>";
        // foreach ($reviews as $review) {
        //     echo "<p><strong>{$review['author_name']}</strong> ({$review['rating']} gwiazdek):<br>";
        //     echo "{$review['text']}<br><br></p>";
        // }

    }
}