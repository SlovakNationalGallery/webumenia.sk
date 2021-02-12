<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Language Lines for autor.blade.php template
    |--------------------------------------------------------------------------
    */

    'artworks'    => '{0}   <a href=":artworks_url"><strong>:artworks_count</strong></a> diel
                     |{1}   <a href=":artworks_url"><strong>:artworks_count</strong></a> dielo
                     |[2,4] <a href=":artworks_url"><strong>:artworks_count</strong></a> diela
                     |[5,*] <a href=":artworks_url"><strong>:artworks_count</strong></a> diel',
    'collections' => '{0}   v <strong>:collections_count</strong> kolekciách
                     |{1}   v <strong>:collections_count</strong> kolekcií
                     |[2,*] v <strong>:collections_count</strong> kolekciách',
    'views'       => '{0}   <strong>:view_count</strong> videní
                     |{1}   <strong>:view_count</strong> videnie
                     |[2,4] <strong>:view_count</strong> videnia
                     |[5,*] <strong>:view_count</strong> videní',

    'tags'              => 'tagy',
    'back-to-artists'   => 'zoznam autorov',
    'alternative_names' => 'príp.',
    'places'            => 'pôsobenie',
    'external_links'    => 'externé odkazy',
    'relationships'     => 'vzťahy',

    'artworks_by_artist' => 'diela autora',

    'button_show-all-artworks' => '{0}   zobraziť <strong>0</strong> diel
                                  |{1}   zobraziť <strong>:artworks_count</strong> dielo
                                  |[2,4] zobraziť všetky <strong>:artworks_count</strong> diela
                                  |[5,*] zobraziť všetkých <strong>:artworks_count</strong> diel',

    'filter' => [
        'role' => 'rola',
        'nationality' => 'príslušnosť',
        'place' => 'miesto',
        'title_generator' => [
            'first_letter' => 'začína sa na: :value',
            'role' => 'rola: :value',
            'nationality' => 'príslušnosť: :value',
            'place' => 'miesto: :value',
            'years' => 'v rokoch :from — :to',
        ],
        'sort_by' => 'podľa',
        'sorting' => [
            'items_with_images_count' => 'počtu diel s obrázkom',
            'name' => 'mena',
            'birth_year' => 'roku narodenia',
            'items_count' => 'počtu diel',
        ],
    ],

    'authors'             => 'autori',
    'authors_found'       => 'nájdení autori pre',
    'authors_counted'     => 'autorov',
    'authors_none'        => 'momentálne žiadni autori',    
    'roles'               => 'role',
    'role'=>[
        "author" => "autor",
        "after" => "autor predlohy",
        "atelier" => "ateliér",
        "circle" => "okruh autora",
        "copyist" => "kopista",
        "draft" => "autor návrhu",
        "drawer" => "kresliar",
        "engraver" => "rytec",
        "epigone" => "napodobňovateľ",
        "follower" => "nasledovník",
        "former" => "pôvodné určenie",
        "graphic" => "grafik",
        "modifier" => "autor úpravy záznamu",
        "office" => "štúdio",
        "original" => "autor originálu",
        "printer" => "tlačiar",
        "probably" => "pravdepodobne",
        "probablyAfter" => "pravdepodobný autor predlohy",
        "probablyCircle" => "pravdepodobne okruh autora",
        "probablyDrawer" => "pravdepodobne kresliar",
        "probablyEngraver" => "pravdepodobne rytec",
        "probablyPrinter" => "pravdepodobne tlačiar",
        "probablyWorkshop" => "pravdepodobne dielňa autora",
        "producer" => "výrobca",
        "publisher" => "vydavateľ",
        "restorer" => "reštaurátor",
        "workshop" => "dielňa autora",
        "concept" => "autor konceptu",
        "photograph" => "autor fotografie",
    ]
);
