<?php

$output .= '
<div class="pwe-store__main-section">
    <div class="pwe-store__main-section-header">
        <img src="/wp-content/plugins/pwe-media/media/store/'. (self::lang_pl() ? 'header_store_pl.webp' : 'header_store_en.webp') .'" alt="Header">
    </div>
    
    <span class="pwe-store__anchor"></span>';

    $categories_header_info = [
        [
            'category' => 'premium',
            'title' => self::lang_pl() 
                ? 'ZWIĘKSZ SWÓJ POTENCJAŁ NA TARGACH: USŁUGI PREMIUM!' 
                : 'INCREASE YOUR POTENTIAL AT TRADE FAIRS: PREMIUM SERVICES!',
            'description' => self::lang_pl() 
                ? 'Skorzystaj z wysokiej jakości rozwiązań, aby wyróżnić się wśród wystawców i przyciągnąć uwagę odwiedzających.' 
                : 'Take advantage of high-quality solutions to stand out among exhibitors and attract the attention of visitors.',
            'max_width' => '700px'
        ],
        [
            'category' => 'marketing',
            'title' => self::lang_pl() 
                ? 'ZWIĘKSZ SWOJĄ WIDOCZNOŚĆ NA TARGACH: USŁUGI MARKETINGOWE!' 
                : 'INCREASE YOUR VISIBILITY AT TRADE FAIRS: MARKETING SERVICES!',
            'description' => self::lang_pl() 
                ? 'Skorzystaj z profesjonalnych strategii marketingowych, aby dotrzeć do większej liczby klientów, zwiększyć rozpoznawalność swojej marki i maksymalnie wykorzystać potencjał targów.' 
                : 'Use professional marketing strategies to reach more customers, increase your brand recognition and maximize the potential of trade fairs.',
            'max_width' => '1200px'
        ],
        [
            'category' => 'social-media',
            'title' => self::lang_pl() 
                ? 'ZWIĘKSZ SWOJĄ WIDOCZNOŚĆ ONLINE: USŁUGI SOCIAL MEDIA!' 
                : 'INCREASE YOUR ONLINE VISIBILITY: SOCIAL MEDIA SERVICES!',
            'description' => self::lang_pl() 
                ? 'Wykorzystaj potencjał mediów społecznościowych, aby dotrzeć do większej liczby klientów, budować zaangażowanie i skutecznie promować swoją obecność na targach.' 
                : 'Use the potential of social media to reach more customers, build engagement and effectively promote your presence at trade shows.',
            'max_width' => '1200px'
        ]
    ];                                

    // Category map
    $categories_map = [];
    foreach ($categories_header_info as $item) {
        $categories_map[$item['category']] = $item;
    }

    // Generate categories header
    foreach ($categories as $category) {
        if (isset($categories_map[$category])) {
            $item_info = $categories_map[$category];
            $output .= '
            <div class="pwe-store__main-section-text '. $category .'">
                <h1>'. $item_info["title"] .'</h1>
                <p style="max-width: '. $item_info["max_width"] .';">'. $item_info["description"] .'</p>
            </div>';
        }
    }

    // Generate category of packages header
    foreach ($packages_categories as $category) {
        $output .= '
        <div class="pwe-store__main-section-text '. $category .'-packages">
            <h1>'. (self::lang_pl() ? "MAKSYMALIZUJ SWOJE MOŻLIWOŚCI: PAKIETY PROMOCYJNE!" : "MAXIMIZE YOUR POSSIBILITIES: PROMOTIONAL PACKAGES!") .'</h1>
            <p style="max-width: 1000px;">'.
                (
                self::lang_pl() 
                ? "Wybierz kompleksowe pakiety usług, które pomogą Ci skutecznie wyróżnić się na targach, zwiększyć zasięg i przyciągnąć więcej klientów." 
                : "Choose comprehensive service packages that will help you effectively stand out at trade fairs, increase your reach and attract more customers."
                ) .'
            </p>
        </div>';
    }
    
$output .= '
</div>';

return $output;