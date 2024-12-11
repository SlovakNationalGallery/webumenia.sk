<?php

return [
    'id' => 'ID',
    'title' => 'název',
    'description' => 'popis',
    'description_source' => 'popis - zdroj',
    'work_type' => 'výtvarný druh',
    'object_type' => 'typ díla',
    'work_level' => 'stupeň spracování',
    'topic' => 'námět',
    'subject' => 'objekt',
    'measurement' => 'rozměry',
    'dating' => 'datace',
    'medium' => 'materiál',
    'technique' => 'technika',
    'inscription' => 'značení',
    'place' => 'místo vzniku',
    'state_edition' => 'původnost',
    'gallery' => 'galerie',
    'credit' => 'nabytí',
    'relationship_type' => 'typ integrity',
    'related_work' => 'název integrity',
    'description_user_id' => 'popis - autor',
    'description_source_link' => 'popis - link na zdroj',
    'identifier' => 'inventární číslo',
    'author' => 'autor / autorka',
    'item_authorities' => 'autoři - vztahy',
    'tags' => 'klíčová slova',
    'tag' => 'klíčové slovo',
    'date_earliest' => 'datace od',
    'date_latest' => 'datace do',
    'lat' => 'latitúda',
    'lng' => 'longitúda',
    'related_work_order' => 'pořadí',
    'related_work_total' => 'z počtu',
    'primary_image' => 'hlavní obrázek',
    'images' => 'obrázky',
    'iipimg_url' => 'IIP URL',
    'filter' => [
        'clear' => 'zrušit výběr',
        'clear_all' => 'zrušit celý výběr',
        'nothing_found' => 'hmm, nic jsme nenašli.',
        'something_went_wrong' => 'ups, něco se pokazilo.',
        'refresh_page' => 'obnovit stránku',
        'title' => 'filtr děl',
        'show_results' => 'show results',
        'show_more' => 'ukaž více',
        'loading' => 'moment...',
        'hide_extended' => 'skrýt další filtry',
        'show_extended' => 'všechny filtry',
        'extended_filter' => 'rozšířený filtr',
        'displaying' => '{1} zobrazeno|[2, 4] zobrazena|[5,*] zobrazeno',
        'artworks_sorted_by' =>
            '{1} dílo seřazené podle|[2, 4] díla seřazené podle |[5,*] děl seřazených podle',
        'try_also' => 'vyzkoušej',
        'random_search' => 'náhodný výběr.',
        'year_from' => 'od roku',
        'year_to' => 'do roku',
        'year' => 'rok',
        'has_image' => 'jen s obrázkem',
        'has_iip' => 'jen se zoom',
        'has_text' => 'jen s textem',
        'is_free' => 'jen volné',
        'color' => 'farba',
        'sort_by' => 'podle',
        'placeholder' => [
            'name_human' => 'Zadej jméno',
            'name_object' => 'Zadej název',
            'term' => 'Zadej pojem',
        ],
        'title_generator' => [
            'search' => 'výsledky vyhledávání pro: ":value"',
            'author' => 'autor: :value',
            'work_type' => 'výtvarný druh: :value',
            'tag' => 'klíčové slovo: :value',
            'gallery' => 'galerie: :value',
            'credit' => 'nabytí: :value',
            'topic' => 'námět: :value',
            'medium' => 'materiál: :value',
            'technique' => 'technika: :value',
            'has_image' => 'jen s obrázkom',
            'has_iip' => 'jen so zoom',
            'is_free' => 'jen voľné',
            'related_work' => 'ze souboru: :value',
            'years' => 'v letech :from — :to',
        ],
        'sorting' => [
            'created_at' => 'data přidání',
            'title' => 'názvu',
            'relevance' => 'relevancie',
            'updated_at' => 'poslední změny',
            'author' => 'autora',
            'newest' => 'datování – od najnovějšího',
            'oldest' => 'datování – od najstaršího',
            'view_count' => 'počtu vidění',
            'random' => 'náhodného pořadí',
        ],
    ],
    'importer' => [
        'work_type' => [
            'graphics' => 'grafika',
            'drawing' => 'kresba',
            'image' => 'obraz',
            'sculpture' => 'sochařství',
            'applied_arts' => 'užité umění',
            'photography' => 'fotografie',
        ],
    ],
    'measurement_replacements' => [
        'a=' => 'výška hlavní části ',
        'a.=' => 'výška hlavní části ',
        'b=' => 'výška vedlejší části ',
        'b.=' => 'výška vedlejší části ',
        'čas=' => 'čas ',
        'd=' => 'délka ',
        'd.=' => 'délka ',
        'h=' => 'hloubka/tloušťka ',
        'h.=' => 'hloubka/tloušťka ',
        'hmot=' => 'hmotnost ',
        'hmot.=' => 'hmotnost ',
        'hr=' => 'hloubka s rámem ',
        'jiný=' => 'jiný nespecifikovaný ',
        'p=' => 'průměr/ráže ',
        'p.=' => 'průměr/ráže ',
        'r.=' => 'ráže ',
        'ryz=' => 'ryzost ',
        's=' => 'šířka ',
        's.=' => 'šířka ',
        'sd.=' => 'šířka grafické desky ',
        'sp=' => 'šířka s paspartou ',
        'sp.=' => 'šířka s paspartou ',
        'sr=' => 'šířka s rámem ',
        'v=' => 'celková výška/délka ',
        'v.=' => 'celková výška/délka ',
        'vd.=' => 'výška grafické desky ',
        'vp=' => 'výška s paspartou ',
        'vp.=' => 'výška s paspartou ',
        'vr=' => 'výška s rámem ',
    ],
    'untitled' => 'bez názvu',
    'authority_roles' => [
        'author' => 'autor',
        'after' => 'autor předlohy',
        'atelier' => 'ateliér',
        'circle' => 'okruh autora',
        'copyist' => 'kopisty',
        'draft' => 'autor návrhu',
        'drawer' => 'kreslíř',
        'engraver' => 'rytec',
        'epigone' => 'napodobitel',
        'follower' => 'následovník',
        'former' => 'původní určení',
        'graphic' => 'grafik',
        'modifier' => 'autor úpravy záznamu',
        'office' => 'studio',
        'original' => 'autor originálu',
        'printer' => 'tiskař',
        'probably' => 'pravděpodobně',
        'probablyAfter' => 'Pravděpodobný autor předlohy',
        'probablyCircle' => 'Pravděpodobně okruh autora',
        'probablyDrawer' => 'pravděpodobně kreslíř',
        'probablyEngraver' => 'pravděpodobně rytec',
        'probablyPrinter' => 'pravděpodobně tiskař',
        'probablyWorkshop' => 'Pravděpodobně dílna autora',
        'producer' => 'výrobcem',
        'publisher' => 'vydavatel',
        'restorer' => 'restaurátor',
        'workshop' => 'dílna autora',
        'concept' => 'autor konceptu',
        'photograph' => 'autor fotografie',
    ],
    'work_types' => [
        'architektúra' => 'architektura',
        'dizajn' => 'design',
        'fotografia' => 'fotografie',
        'fotografia/negatív' => 'fotografie/negativ',
        'grafický dizajn' => 'grafický design',
        'grafika' => 'grafika',
        'iné médiá/video' => 'jiná média/video',
        'kresba' => 'kresba',
        'maliarstvo' => 'malířství',
        'multimédiá' => 'multimédia',
        'sochárstvo/plastika' => 'sochařství/plastika',
        'sochárstvo/skulptúra' => 'sochařství/skulptura',
        'úžitkové umenie' => 'užité umění',
    ],
    'media' => [
        'akryl, valček, sololit' => 'akryl, váleček, sololit',
        'bakelit' => 'bakelit',
        'chrom' => 'chrom',
        'drevo dubové' => 'dřevo dubové',
        'drevo javorové' => 'dřevo javorové',
        'drevo' => 'dřevo',
        'drevotrieska' => 'dřevotříska',
        'hliník' => 'hliník',
        'kartón' => 'karton',
        'kombinácie' => 'kombinace',
        'kov' => 'kov',
        'molitan' => 'molitan',
        'papier kartón' => 'papír kartón',
        'papier polokartón podlepený' => 'papír polokarton podlepený',
        'papier polokartón' => 'papír polokarton',
        'papier ručný' => 'papír ruční',
        'papier s emulziou podlepená' => 'papír s emulzí podlepená',
        'papier s emulziou' => 'papír s emulzí',
        'papier' => 'papír',
        'papier/matný' => 'papír/matný',
        'papier/pastelový papier' => 'papír/pastelový papír',
        'plast' => 'plast',
        'plastická (umelá) hmota' => 'plastická (umělá) hmota',
        'plátno' => 'plátno',
        'porcelán' => 'porcelán',
        'preglejka' => 'překližka',
        'sklo krištáľové farebné' => 'sklo hutní barevné',
        'sklo' => 'sklo',
        'sklolaminát' => 'sklolaminát',
        'textil' => 'textil',
        'vosk' => 'vosk',
        'železo' => 'železo',
    ],
    'techniques' => [
        'akvarel' => 'akvarel',
        'akvarel/čierny' => 'akvarel/černý',
        'akvatinta' => 'akvatinta',
        'bromolejotlač' => 'bromolejotisk',
        'bromostrieborná fotografia' => 'bromostříbrná fotografie',
        'chromovanie nohy' => 'chromování nohy',
        'cibachrom farebná' => 'cibachrom barevná',
        'digitálna fotografia, C-print' => 'digitální fotografie, C-print',
        'drevoryt (xylografie) čiernobiely' => 'dřevoryt (xylografie) černobílý',
        'drevoryt (xylografie)' => 'dřevoryt (xylografie)',
        'drevoryt' => 'dřevořez',
        'farebná fotografia' => 'barvená fotografie',
        'fotografia farebná' => 'fotografie barevná',
        'fotografia kolorovaná' => 'fotografie kolorovaná',
        'fotografia platinotypie' => 'fotografie platinotypie',
        'fotografia čiernobiela' => 'fotografie černobílá',
        'fotografia' => 'fotografie',
        'fotomontáž' => 'fotomontáž',
        'frézovanie' => 'frézování',
        'glazovanie' => 'glazování',
        'grafit' => 'grafit',
        'hutné tvarovanie' => 'hutní tvarování',
        'iná technika' => 'jiná technika',
        'knihtlač farebná' => 'knihtisk barevný',
        'knihtlač čiernobiela' => 'knihtisk černobílý',
        'knihtlač' => 'knihtisk',
        'kolorovanie' => 'kolorování',
        'koláž' => 'koláž',
        'kombinovaná technika čiernobiela' => 'kombinovaná technika černobílá',
        'kombinovaná technika' => 'kombinovaná technika',
        'kontaktná kópia' => 'kontaktní kópie',
        'kresba - iná technika' => 'kresba - jiná technika',
        'kresba farebná' => 'kresba barevná',
        'kresba čiernobiela' => 'kresba černobílá',
        'kresba' => 'kresba',
        'lepenie' => 'lepení',
        'lept farebný' => 'lept barevný',
        'lept' => 'lept',
        'liatie' => 'lití',
        'linoryt farebný' => 'linoryt barevný',
        'linoryt čiernobiely' => 'linoryt černobílý',
        'linoryt' => 'linoryt',
        'lisovanie biely' => 'lisování bílý',
        'litografia farebná' => 'litografie barevná',
        'litografia čiernobiela' => 'litografie černobílá',
        'litografia' => 'litografie',
        'montáž' => 'montáž',
        'odlievanie' => 'odlévání',
        'ofset autorský farebný' => 'ofset autorský barevný',
        'ofset autorský čiernobiely' => 'ofset autorský černobílá',
        'ofset autorský' => 'ofset autorský',
        'ohýbanie' => 'ohýbání',
        'olej' => 'olej',
        'roláž čiernobiela' => 'roláž černobílá',
        'rytina' => 'rytina',
        'serigrafia farebná' => 'serigrafie barevná',
        'serigrafia' => 'serigrafie',
        'slepotlač' => 'slepotisk',
        'strojopis' => 'strojopis',
        'suchá ihla čiernobiela' => 'suchá jehla černobílá',
        'suchá ihla' => 'suchá jehla',
        'tempera farebná' => 'tempera barevná',
        'tlač farebná' => 'tisk barevný',
        'tlač' => 'tisk',
        'tuš' => 'tuš',
        'typografia farebná' => 'typografie barevná',
        'typografia čiernobiela' => 'typografie černobílá',
        'typografia' => 'typografie',
        'tónovanie' => 'tónování',
        'vypaľovanie v peci' => 'vypalování v peci',
        'zmenšovanie' => 'zmenšování',
        'zväčšovanie' => 'zvětšování',
        'čiernobiela fotografia' => 'černobílá fotografie',
    ],
    'topics' => [
        'figurálna kompozícia' => 'figurální kompozice',
    ],
    'state_editions' => [
        'autorizovaný pozitív' => 'autorizovaný pozitiv',
        'faksimile' => 'faksimile',
        'iný' => 'jiný',
        'kópia' => 'kopie',
        'neautorizovaný pozitív' => 'neautorský pozitiv',
        'neznámy' => 'neznámý',
        'originál' => 'originál',
        'reprodukcia' => 'reprodukce',
        'tlačová reprodukcia' => 'tisková reprodukce',
    ],
];
