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
        'clear' => 'zrušiť výber',
        'clear_all' => 'zrušiť celý výber',
        'nothing_found' => 'hmm, nič sme nenašli.',
        'something_went_wrong' => 'ups, niečo sa pokazilo.',
        'refresh_page' => 'obnoviť stránku',
        'title' => 'filter diel',
        'show_results' => 'zobraziť výsledky',
        'show_more' => 'ukáž viac',
        'loading' => 'moment...',
        'show_extended' => 'všetky filtre',
        'hide_extended' => 'skryť ďalšie filtre',
        'extended_filter' => 'rozšírený filter',
        'displaying' => 'zobrazujem',
        'artworks_sorted_by' =>
            '{1} dielo zoradené podľa|[2, 4] diela zoradené podľa |[5,*] diel zoradených podľa',
        'try_also' => 'vyskúšaj',
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
        'sorting' => [
            'created_at' => 'dátumu pridania',
            'title' => 'názvu',
            'relevance' => 'relevancie',
            'updated_at' => 'poslednej zmeny',
            'author' => 'autora',
            'newest' => 'datovania – od najnovšieho',
            'oldest' => 'datovania – od najstaršieho',
            'view_count' => 'počtu videní',
            'random' => 'náhodného poradia',
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
    'untitled' => 'bez názvu',
    'authority_roles' => [
        'author' => 'autor',
        'after' => 'autor predlohy',
        'atelier' => 'ateliér',
        'circle' => 'okruh autora',
        'copyist' => 'kopista',
        'draft' => 'autor návrhu',
        'drawer' => 'kresliar',
        'engraver' => 'rytec',
        'epigone' => 'napodobňovateľ',
        'follower' => 'nasledovník',
        'former' => 'pôvodné určenie',
        'graphic' => 'grafik',
        'modifier' => 'autor úpravy záznamu',
        'office' => 'štúdio',
        'original' => 'autor originálu',
        'printer' => 'tlačiar',
        'probably' => 'pravdepodobne',
        'probablyAfter' => 'pravdepodobný autor predlohy',
        'probablyCircle' => 'pravdepodobne okruh autora',
        'probablyDrawer' => 'pravdepodobne kresliar',
        'probablyEngraver' => 'pravdepodobne rytec',
        'probablyPrinter' => 'pravdepodobne tlačiar',
        'probablyWorkshop' => 'pravdepodobne dielňa autora',
        'producer' => 'výrobca',
        'publisher' => 'vydavateľ',
        'restorer' => 'reštaurátor',
        'workshop' => 'dielňa autora',
        'concept' => 'autor konceptu',
        'photograph' => 'autor fotografie',
    ],
    'work_types' => [
        'architektúra' => 'architektúra',
        'dizajn' => 'dizajn',
        'fotografia' => 'fotografia',
        'fotografia/negatív' => 'fotografia/negatív',
        'grafika' => 'grafika',
        'grafický dizajn' => 'grafický dizajn',
        'iné médiá/video' => 'iné médiá/video',
        'kresba' => 'kresba',
        'maliarstvo' => 'maliarstvo',
        'multimédiá' => 'multimédiá',
        'sochárstvo/plastika' => 'sochárstvo/plastika',
        'sochárstvo/skulptúra' => 'sochárstvo/skulptúra',
        'úžitkové umenie' => 'úžitkové umenie',
    ],
    'media' => [
        'akryl, valček, sololit' => 'akryl, valček, sololit',
        'bakelit' => 'bakelit',
        'chrom' => 'chrom',
        'drevo dubové' => 'drevo dubové',
        'drevo javorové' => 'drevo javorové',
        'drevo' => 'drevo',
        'drevotrieska' => 'drevotrieska',
        'hliník' => 'hliník',
        'kartón' => 'kartón',
        'kombinácie' => 'kombinácie',
        'kov' => 'kov',
        'molitan' => 'molitan',
        'papier kartón' => 'papier kartón',
        'papier polokartón podlepený' => 'papier polokartón podlepený',
        'papier polokartón' => 'papier polokartón',
        'papier ručný' => 'papier ručný',
        'papier s emulziou podlepená' => 'papier s emulziou podlepená',
        'papier s emulziou' => 'papier s emulziou',
        'papier' => 'papier',
        'papier/matný' => 'papier/matný',
        'papier/pastelový papier' => 'papier/pastelový papier',
        'plast' => 'plast',
        'plastická (umelá) hmota' => 'plastická (umelá) hmota',
        'plátno' => 'plátno',
        'porcelán' => 'porcelán',
        'preglejka' => 'preglejka',
        'sklo krištáľové farebné' => 'sklo krištáľové farebné',
        'sklo' => 'sklo',
        'sklolaminát' => 'sklolaminát',
        'textil' => 'textil',
        'vosk' => 'vosk',
        'železo' => 'železo',
    ],
    'techniques' => [
        'akvarel' => 'akvarel',
        'akvarel/čierny' => 'akvarel/čierny',
        'akvatinta' => 'akvatinta',
        'bromolejotlač' => 'bromolejotlač',
        'bromostrieborná fotografia' => 'bromostrieborná fotografia',
        'chromovanie nohy' => 'chromovanie nohy',
        'cibachrom farebná' => 'cibachrom farebná',
        'digitálna fotografia, C-print' => 'digitálna fotografia, C-print',
        'drevoryt (xylografie) čiernobiely' => 'drevoryt (xylografie) čiernobiely',
        'drevoryt (xylografie)' => 'drevoryt (xylografie)',
        'drevoryt' => 'drevoryt',
        'farebná fotografia' => 'farebná fotografia',
        'fotografia farebná' => 'fotografia farebná',
        'fotografia kolorovaná' => 'fotografia kolorovaná',
        'fotografia platinotypie' => 'fotografia platinotypie',
        'fotografia čiernobiela' => 'fotografia čiernobiela',
        'fotografia' => 'fotografia',
        'fotomontáž' => 'fotomontáž',
        'frézovanie' => 'frézovanie',
        'glazovanie' => 'glazovanie',
        'grafit' => 'grafit',
        'hutné tvarovanie' => 'hutné tvarovanie',
        'iná technika' => 'iná technika',
        'knihtlač farebná' => 'knihtlač farebná',
        'knihtlač čiernobiela' => 'knihtlač čiernobiela',
        'knihtlač' => 'knihtlač',
        'kolorovanie' => 'kolorovanie',
        'koláž' => 'koláž',
        'kombinovaná technika čiernobiela' => 'kombinovaná technika čiernobiela',
        'kombinovaná technika' => 'kombinovaná technika',
        'kontaktná kópia' => 'kontaktná kópia',
        'kresba - iná technika' => 'kresba - iná technika',
        'kresba farebná' => 'kresba farebná',
        'kresba čiernobiela' => 'kresba čiernobiela',
        'kresba' => 'kresba',
        'lepenie' => 'lepenie',
        'lept farebný' => 'lept farebný',
        'lept' => 'lept',
        'liatie' => 'liatie',
        'linoryt farebný' => 'linoryt farebný',
        'linoryt čiernobiely' => 'linoryt čiernobiely',
        'linoryt' => 'linoryt',
        'lisovanie biely' => 'lisovanie biely',
        'litografia farebná' => 'litografia farebná',
        'litografia čiernobiela' => 'litografia čiernobiela',
        'litografia' => 'litografia',
        'montáž' => 'montáž',
        'odlievanie' => 'odlievanie',
        'ofset autorský farebný' => 'ofset autorský farebný',
        'ofset autorský čiernobiely' => 'ofset autorský čiernobiely',
        'ofset autorský' => 'ofset autorský',
        'ohýbanie' => 'ohýbanie',
        'olej' => 'olej',
        'roláž čiernobiela' => 'roláž čiernobiela',
        'rytina' => 'rytina',
        'serigrafia farebná' => 'serigrafia farebná',
        'serigrafia' => 'serigrafia',
        'slepotlač' => 'slepotlač',
        'strojopis' => 'strojopis',
        'suchá ihla čiernobiela' => 'suchá ihla čiernobiela',
        'suchá ihla' => 'suchá ihla',
        'tempera farebná' => 'tempera farebná',
        'tlač farebná' => 'tlač farebná',
        'tlač' => 'tlač',
        'tuš' => 'tuš',
        'typografia farebná' => 'typografia farebná',
        'typografia čiernobiela' => 'typografia čiernobiela',
        'typografia' => 'typografia',
        'tónovanie' => 'tónovanie',
        'vypaľovanie v peci' => 'vypaľovanie v peci',
        'zmenšovanie' => 'zmenšovanie',
        'zväčšovanie' => 'zväčšovanie',
        'čiernobiela fotografia' => 'čiernobiela fotografia',
    ],
    'topics' => [
        'figurálna kompozícia' => 'figurálna kompozícia',
    ],
    'state_editions' => [
        'autorizovaný pozitív' => 'autorizovaný pozitív',
        'faksimile' => 'faksimile',
        'iný' => 'iný',
        'kópia' => 'kópia',
        'neautorizovaný pozitív' => 'neautorizovaný pozitív',
        'neznámy' => 'neznámy',
        'originál' => 'originál',
        'reprodukcia' => 'reprodukcia',
        'tlačová reprodukcia' => 'tlačová reprodukcia',
    ],
];
