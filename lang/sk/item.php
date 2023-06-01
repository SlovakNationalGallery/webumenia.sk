<?php

return [
    'id' => 'ID',
    'title' => 'názov',
    'description' => 'popis',
    'description_source' => 'popis - zdroj',
    'work_type' => 'výtvarný druh',
    'object_type' => 'typ diela',
    'work_level' => 'stupeň spracovania',
    'topic' => 'žáner',
    'subject' => 'objekt',
    'measurement' => 'miery',
    'dating' => 'datovanie',
    'medium' => 'materiál',
    'technique' => 'technika',
    'inscription' => 'značenie',
    'place' => 'geografická oblasť',
    'state_edition' => 'stupeň spracovania',
    'gallery' => 'galéria',
    'credit' => 'nadobudnutie',
    'relationship_type' => 'typ integrity',
    'related_work' => 'názov integrity',
    'description_user_id' => 'popis - autor',
    'description_source_link' => 'popis - link na zdroj',
    'identifier' => 'inventárne číslo',
    'author' => 'autor / autorka',
    'item_authorities' => 'autori - vzťahy',
    'tags' => 'tagy',
    'tag' => 'tag',
    'date_earliest' => 'datovanie najskôr',
    'date_latest' => 'datovanie najneskôr',
    'lat' => 'latitúda',
    'lng' => 'longitúda',
    'related_work_order' => 'poradie',
    'related_work_total' => 'z počtu',
    'primary_image' => 'hlavný obrázok',
    'images' => 'obrázky',
    'iipimg_url' => 'IIP URL',
    'filter' => [
        'show_more' => 'zobraziť viac',
        'loading' => 'moment...',
        'show_extended' => 'všetky filtre',
        'hide_extended' => 'skryť ďalšie filtre',
        'extended_filter' => 'rozšírený filter',
        'displaying' => 'zobrazujem',
        'artworks_sorted_by' => '{1}dielo zoradené podľa|[2, 4]diela zoradené podľa |[5,*] diel zoradených podľa',
        'try_also' => 'vyskúšaj tiež',
        'random_search' => 'náhodný výber.',
        'year_from' => 'od roku',
        'year_to' => 'do roku',
        'year' => 'rok',
        'has_image' => 'len s obrázkom',
        'has_iip' => 'len so zoom',
        'has_text' => 'len s textom',
        'is_free' => 'len voľné',
        'color' => 'farba',
        'sort_by' => 'podľa',
        'placeholder' => [
            'name_human' => 'Zadaj meno',
            'name_object' => 'Zadaj názov',
            'term' => 'Zadaj pojem',
        ],    
        'title_generator' => [
            'search' => 'výsledky vyhľadávania pre: ":value"',
            'author' => 'autor: :value',
            'work_type' => 'výtvarný druh: :value',
            'tag' => 'tag: :value',
            'gallery' => 'galéria: :value',
            'credit' => 'nadobudnutie: :value',
            'topic' => 'žáner: :value',
            'medium' => 'materiál: :value',
            'technique' => 'technika: :value',
            'related_work' => 'zo súboru: :value',
            'years' => 'v rokoch :from — :to',
        ],
    ],
    'importer' => [
        'work_type' => [
            'graphics' => 'grafika',
            'drawing' => 'kresba',
            'image' => 'obraz',
            'sculpture' => 'sochárstvo',
            'applied_arts' => 'úžitkové umenie',
            'photography' => 'fotografia',
        ],
    ],
    'measurement_replacements' => [
        'a' => 'výška hlavnej časti',
        'a.' => 'výška hlavnej časti',
        'b' => 'výška vedľajšej časti',
        'b.' => 'výška vedľajšej časti',
        'čas' => 'čas',
        'd' => 'dĺžka',
        'd.' => 'dĺžka',
        'h' => 'hĺbka/hrúbka',
        'h.' => 'hĺbka/hrúbka',
        'hmot' => 'hmotnosť',
        'hmot.' => 'hmotnosť',
        'hr' => 'hĺbka s rámom',
        'jiný' => 'nešpecifikované',
        'p' => 'priemer',
        'p.' => 'priemer',
        'r.' => 'ráž',
        'ryz' => 'rýdzosť',
        's' => 'šírka',
        's.' => 'šírka',
        'sd.' => 'šírka grafickej plochy',
        'sp' => 'šírka s paspartou',
        'sp.' => 'šírka s paspartou',
        'sr' => 'šírka s rámom',
        'v' => 'celková výška/dĺžka',
        'v.' => 'celková výška/dĺžka',
        'vd.' => 'výška grafickej dosky',
        'vp' => 'výška s paspartou',
        'vp.' => 'výška s paspartou',
        'vr' => 'výška s rámom',
        '=' => ' ',
    ],
];