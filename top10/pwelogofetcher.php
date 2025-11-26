<?php
/*
class PWELogoFetcher extends PWECommonFunctions {

    private string $katalog_id;
    private string $secret = '#22targiexpo22@@@#';
    private int $limit = 10;
    private string $placeholderPath;
    private int $fair_id;

    public function __construct(string $placeholderPath)
    {
        $this->placeholderPath = $placeholderPath;
        $current_domain = $_SERVER['HTTP_HOST'];

        // Pobierz dane targów z bazy
        $fair_data = PWECommonFunctions::get_database_fairs_data($current_domain);

        // Ustal ID targów
        $this->fair_id = isset($fair_data[0]) ? (int) $fair_data[0]->fair_kw : 0;

        $this->katalog_id = (string) $this->fair_id;

        add_action('init', array($this, 'initVCMapPWELogoFetcher'));
        add_shortcode('pwe_logofetcher', array($this, 'PWELogoFetcherOutput'));

        add_action('init', function () {
            if (isset($_GET['logotyp'])) {
                $this->handleRequest();
            }
        });
    }

    public function initVCMapPWELogoFetcher()
    {
      if (class_exists('Vc_Manager')) {
        vc_map(array(
            'name' => __('PWE Logo Fetcher', 'pwe_logofetcher'),
            'base' => 'pwe_logofetcher',
            'class' => '',
            'category' => __('PWE Elements', 'pwe_logofetcher'),
            'description' => __('Wyświetla logotypy z API'),
            'params' => [

            ],
        ));
      }
    }
    public function handleRequest(): void
    {
        if (!isset($_GET['logotyp'])) {
            $this->displayApiData();
        } else {
            $this->serveLogo((int)$_GET['logotyp']);
        }
    }

    private function generateToken(): string
    {
        return md5($this->secret . date('Y-m-d'));
    }

    private function getApiUrl(): string
    {
        $exh_catalog_address = PWECommonFunctions::get_database_meta_data('exh_catalog_address');
        return $exh_catalog_address . $this->generateToken() . '&id_targow=' . $this->katalog_id;
    }

    private function fetchApiData(): ?array
    {
        $json = @file_get_contents($this->getApiUrl());

        if ($json === false) {
            return null;
        }

        return json_decode($json, true);
    }

    private function displayApiData(): void
    {
        $data = $this->fetchApiData();
        if ($data === null) {
            echo "<strong>Błąd:</strong> Nie udało się pobrać danych z API.";
            exit;
        }

        echo "<h2>Dane z API</h2><pre>";
        print_r($data);
        echo "</pre>";
        exit;
    }

    private function serveLogo(int $index): void
    {
        $requestedIndex = $index - 1;
        if ($requestedIndex < 0 || $requestedIndex >= $this->limit) {
            $this->sendPlaceholder();
        }

        $data = $this->fetchApiData();
        if ($data === null) {
            $this->sendPlaceholder();
        }

        $wystawcy = reset($data)['Wystawcy'] ?? [];
        $logotypy = [];

        foreach ($wystawcy as $wystawca) {
            if (!empty($wystawca['URL_logo_wystawcy'])) {
                $logotypy[] = $wystawca['URL_logo_wystawcy'];
            }
            if (count($logotypy) >= $this->limit) {
                break;
            }
        }

        if (!isset($logotypy[$requestedIndex])) {
            $this->sendPlaceholder();
        }

        $logoUrl = $logotypy[$requestedIndex];
        $imageContent = @file_get_contents($logoUrl);

        if ($imageContent === false) {
            $this->sendPlaceholder();
        }

        $extension = strtolower(pathinfo($logoUrl, PATHINFO_EXTENSION));
        $contentTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png'
        ];

        header("Content-Type: " . ($contentTypes[$extension] ?? 'image/jpeg'));
        echo $imageContent;
        exit;
    }


    private function sendPlaceholder(): void
    {
        http_response_code(404);
        header("Content-Type: image/png");
        readfile($this->placeholderPath);
        exit;
    }
    public function PWELogoFetcherOutput($atts) {
        $output_html = '<div class="pwe-logo-fetcher">';

        for ($i = 1; $i <= $this->limit; $i++) {
            $img_url = site_url('/?logotyp=' . $i);
            $output_html .= '<img src="' . esc_url($img_url) . '" alt="Logo ' . $i . '" style="max-height:100px; margin: 10px;" />';
        }

        $output_html .= '</div>';
        return $output_html;
    }
}
