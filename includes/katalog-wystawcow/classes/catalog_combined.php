<?php
class PWECatalogCombined extends PWECatalog {

    /**
     * Constructor method.
    * Calls parent constructor and adds an action for initializing the Visual Composer map.
    */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Static method to initialize Visual Composer elements.
     * Returns an array of parameters for the Visual Composer element.
     */
    // public static function initElements() {
    // }
    public static function output($atts, $identification) {
        $catalog_display_duplicate = isset($atts['catalog_display_duplicate']) ? $atts['catalog_display_duplicate'] : false;
        $pwecatalog_display_random = isset($atts['pwecatalog_display_random1']) ? $atts['pwecatalog_display_random1'] : false;
        $file_changer = isset($atts['file_changer']) ? $atts['file_changer'] : null;

        $identification = str_replace(' ', '', $identification);
        $ids = explode(',', $identification);

        $output = '';

        $merge_exhibitors = [];

        foreach ($ids as $id) {
            $exhibitors = CatalogFunctions::logosChecker($id, $atts['format'], $pwecatalog_display_random, $file_changer, $catalog_display_duplicate);

            if (is_array($exhibitors)) {
                foreach ($exhibitors as $exhibitor) {
                    // Dodajemy ID katalogu do każdego wystawcy
                    $exhibitor['id_katalogu'] = $id;
                    $merge_exhibitors[] = $exhibitor;
                }
            }
        }

        shuffle($merge_exhibitors); // Losuje kolejność
        $front_logos = array_slice($merge_exhibitors, 0, 19); // Pierwsze 19 na front
        $remaining_logos = array_slice($merge_exhibitors, 19); // Reszta do losowania na back

        $count = ceil(count($merge_exhibitors) / 50) * 50;

        $output .= '
        <style>
            #katalog-'. self::$rnd_id .' .combined-catalog__columns {
                display: flex;
                gap: 18px;
                justify-content: center;
                align-items: center;
                max-width: 1200px;
                margin: 32px auto;
            }
            #katalog-'. self::$rnd_id .' .combined-catalog__logo-column {
                display: flex;
                flex-direction: column;
                gap: 18px;
            }
            #katalog-'. self::$rnd_id .' :is(.col-2, .col-4, .col-6) {
                margin-top: 50px;
            } 
            #katalog-'. self::$rnd_id .' :is(.col-3, .col-5) {
                margin-top: -50px;
            }   
            #katalog-'. self::$rnd_id .' :is(.col-1, .col-7) {
                margin-top: 50px;
            }
            #katalog-'. self::$rnd_id .' .combined-catalog__logo-tile {
                width: 140px;
                height: auto;
                aspect-ratio: 4 / 3;
                perspective: 1000px;
            }
            #katalog-'. self::$rnd_id .' .combined-catalog__flip-card {
                width: 100%;
                height: 100%;
            }
            #katalog-'. self::$rnd_id .' .combined-catalog__flip-card-inner {
                width: 100%;
                height: 100%;
                transition: transform 0.7s cubic-bezier(.4,2,.3,1);
                transform-style: preserve-3d;
                position: relative;
            }
            #katalog-'. self::$rnd_id .' .flipped .combined-catalog__flip-card-inner {
                transform: rotateY(180deg);
            }
            #katalog-'. self::$rnd_id .' .combined-catalog__flip-card-front, .combined-catalog__flip-card-back {
                position: absolute;
                width: 100%;
                height: 100%;
                background: #fff;
                border-radius: 20px;
                box-shadow: 0 4px 16px #0002;
                display: flex;
                align-items: center;
                justify-content: center;
                backface-visibility: hidden;
            }
            #katalog-'. self::$rnd_id .' .combined-catalog__flip-card-front img, 
            #katalog-'. self::$rnd_id .' .combined-catalog__flip-card-back img {
                max-width: 90%;
                aspect-ratio: 3 / 2;
                object-fit: contain;
            }
            #katalog-'. self::$rnd_id .' .combined-catalog__flip-card-back {
                transform: rotateY(180deg);
            }


            #katalog-'. self::$rnd_id .' .combined-catalog__button {
                display: flex;
                max-width: 300px;
                margin: 18px auto 0;
                text-align: center;
                background: #F8F8F8;
                padding: 20px;
                border-radius: 12px;
            }
            #katalog-'. self::$rnd_id .' .combined-catalog__button a {
                margin: 0 auto;
                font-weight: 700;
            }
            @media(max-width: 1200px) {
                #katalog-'. self::$rnd_id .' .combined-catalog__columns {
                    flex-direction: column;
                }
                #katalog-'. self::$rnd_id .' .combined-catalog__logo-column {
                    flex-direction: row;
                }
                #katalog-'. self::$rnd_id .' :is(.col-2, .col-4, .col-6, .col-3, .col-5, .col-1, .col-7) {
                    margin-top: 0;
                } 
            }
            @media(max-width: 550px) { 
                #katalog-'. self::$rnd_id .' .combined-catalog__columns {
                    gap: 10px;
                }
                #katalog-'. self::$rnd_id .' .combined-catalog__logo-column {
                    gap: 10px;
                }
                #katalog-'. self::$rnd_id .' .combined-catalog__logo-tile {
                    width: 120px;
                }
            }
            @media(max-width: 400px) { 
                #katalog-'. self::$rnd_id .' .combined-catalog__logo-tile {
                    width: 100px;
                }
            }
        </style>';

        // Generuj HTML
        $output .= '
        <div id="combinedCatalog" class="combined-catalog">
            <div class="combined-catalog__columns">';

            // Number of logos in each column
            $column_logo_counts = [2, 3, 3, 3, 3, 3, 2];
            $index = 0;

            for ($col = 0; $col < 7; $col++) {
                $output .= '<div class="combined-catalog__logo-column col-' . ($col+1) . '">';
                for ($j = 0; $j < $column_logo_counts[$col]; $j++) {
                    $img = htmlspecialchars($front_logos[$index]['URL_logo_wystawcy']);
                    $name = htmlspecialchars($front_logos[$index]['Nazwa_wystawcy']);
                    $idk = htmlspecialchars($front_logos[$index]['id_katalogu']);
                    $output .= '
                    <div class="combined-catalog__logo-tile" data-index="'.$index.'">
                        <div class="combined-catalog__flip-card">
                            <div class="combined-catalog__flip-card-inner">
                                <div class="combined-catalog__flip-card-front">
                                    <img src="'.$img.'" alt="'.$name.'">
                                </div>
                                <div class="combined-catalog__flip-card-back"></div>
                            </div>
                        </div>
                    </div>';
                    $index++;
                }
                $output .= '</div>';
            }

            $output .= '
            </div>

            <div class="combined-catalog__button">
                <a href="/test-anton/">zobacz wszystkich<br>wystawców '. $count .'+</a>
            </div>

        </div>';

        $output .= '
        <script>
            const tileCount = 19;
            const allLogos = '.json_encode($remaining_logos).';

            // Table of tiles and their current logos
            const tiles = document.querySelectorAll(".combined-catalog__logo-tile");
            let displayedLogos = []; // logo wyświetlane na kafelkach
            let availableLogos = [...allLogos]; // logo do podmiany

            // Choose 19 unique logos to start with
            function pickUniqueLogos(source, n) {
                const copy = [...source];
                const picked = [];
                for(let i=0; i<n && copy.length; i++) {
                    const idx = Math.floor(Math.random() * copy.length);
                    picked.push(copy.splice(idx, 1)[0]);
                }
                return picked;
            }

            displayedLogos = pickUniqueLogos(availableLogos, tileCount);
            // Remove the displayed logo from the available ones
            displayedLogos.forEach(logo => {
                const idx = availableLogos.findIndex(l => l.URL_logo_wystawcy === logo.URL_logo_wystawcy);
                if(idx !== -1) availableLogos.splice(idx, 1);
            });

            // Insert the logo onto the tiles
            tiles.forEach((tile, idx) => {
                let logo = displayedLogos[idx];
                tile.querySelector(".combined-catalog__flip-card-front").innerHTML = `<img src="${logo.URL_logo_wystawcy}" alt="${logo.Nazwa_wystawcy}">`;
                // Losujemy pierwsze back z puli (mogą być już wyczerpane – wtedy znowu miksujemy całość)
                let backLogo = pickUniqueLogos(availableLogos, 1)[0] || allLogos[Math.floor(Math.random() * allLogos.length)];
                tile.querySelector(".combined-catalog__flip-card-back").innerHTML = `<img src="${backLogo.URL_logo_wystawcy}" alt="${backLogo.Nazwa_wystawcy}">`;
            });

            let isFlipping = false;
            let flipState = Array(tiles.length).fill(false); // zapamiętuje stan kafelków

            function flipRandomTiles() {
                if(isFlipping) return;
                isFlipping = true;

                // Get the indexes of all tiles
                const tileIndices = Array.from({length: tiles.length}, (_, i) => i);

                // Randomly select a unique tile index
                const shuffled = tileIndices.sort(() => 0.5 - Math.random());
                const selected = shuffled.slice(0, 1);

                selected.forEach(tileIdx => {
                    const tile = tiles[tileIdx];
                    const frontDiv = tile.querySelector(".combined-catalog__flip-card-front");
                    const backDiv = tile.querySelector(".combined-catalog__flip-card-back");

                    // Randomize new logo on back (unique)
                    let unused = availableLogos.filter(l => !displayedLogos.find(d => d.URL_logo_wystawcy === l.URL_logo_wystawcy));
                    if(unused.length === 0) {
                        availableLogos.push(displayedLogos[tileIdx]);
                        unused = availableLogos.filter(l => !displayedLogos.find(d => d.URL_logo_wystawcy === l.URL_logo_wystawcy));
                    }
                    let newBackLogo = pickUniqueLogos(unused, 1)[0] || displayedLogos[tileIdx];

                    // Change the logo on the "other side"
                    if (!flipState[tileIdx]) {
                        backDiv.innerHTML = `<img src="${newBackLogo.URL_logo_wystawcy}" alt="${newBackLogo.Nazwa_wystawcy}">`;
                        tile.classList.add("flipped");
                    } else {
                        frontDiv.innerHTML = `<img src="${newBackLogo.URL_logo_wystawcy}" alt="${newBackLogo.Nazwa_wystawcy}">`;
                        tile.classList.remove("flipped");
                    }
                    flipState[tileIdx] = !flipState[tileIdx];

                    // After the animation is finished, replace the logo from the pool (asynchronously)
                    setTimeout(() => {
                        availableLogos.push(displayedLogos[tileIdx]);
                        displayedLogos[tileIdx] = newBackLogo;
                        const idxToRemove = availableLogos.findIndex(l => l.URL_logo_wystawcy === newBackLogo.URL_logo_wystawcy);
                        if(idxToRemove !== -1) availableLogos.splice(idxToRemove, 1);

                        // Nie ustawiaj isFlipping=false tutaj, bo może być kilka setTimeoutów!
                    }, 700);
                });

                // Unlock the flip after 0.7s (when the longest flip ends)
                setTimeout(() => {
                    isFlipping = false;
                }, 700);
            }

            // Function to flip a specific tile
            function flipTile(tileIdx) {
                const tile = tiles[tileIdx];
                const frontDiv = tile.querySelector(".combined-catalog__flip-card-front");
                const backDiv = tile.querySelector(".combined-catalog__flip-card-back");

                // Randomize new logo on back (unique)
                let unused = availableLogos.filter(l => !displayedLogos.find(d => d.URL_logo_wystawcy === l.URL_logo_wystawcy));
                if(unused.length === 0) {
                    availableLogos.push(displayedLogos[tileIdx]);
                    unused = availableLogos.filter(l => !displayedLogos.find(d => d.URL_logo_wystawcy === l.URL_logo_wystawcy));
                }
                let newBackLogo = pickUniqueLogos(unused, 1)[0] || displayedLogos[tileIdx];

                // Change the logo on the "other side"
                if (!flipState[tileIdx]) {
                    backDiv.innerHTML = `<img src="${newBackLogo.URL_logo_wystawcy}" alt="${newBackLogo.Nazwa_wystawcy}">`;
                    tile.classList.add("flipped");
                } else {
                    frontDiv.innerHTML = `<img src="${newBackLogo.URL_logo_wystawcy}" alt="${newBackLogo.Nazwa_wystawcy}">`;
                    tile.classList.remove("flipped");
                }
                flipState[tileIdx] = !flipState[tileIdx];

                setTimeout(() => {
                    availableLogos.push(displayedLogos[tileIdx]);
                    displayedLogos[tileIdx] = newBackLogo;
                    const idxToRemove = availableLogos.findIndex(l => l.URL_logo_wystawcy === newBackLogo.URL_logo_wystawcy);
                    if(idxToRemove !== -1) availableLogos.splice(idxToRemove, 1);
                }, 700);
            }

            // Click handling
            tiles.forEach((tile, idx) => {
                tile.addEventListener("click", function() {
                    flipTile(idx);
                });
            });
            
            setInterval(flipRandomTiles, 2500);
        </script>';

        return $output;
    }
}