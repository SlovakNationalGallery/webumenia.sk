<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Language Lines for autor.blade.php template
    |--------------------------------------------------------------------------
    */

    'artworks'    => '{0}   <a href=":artworks_url"><strong>:artworks_count</strong></a> díl
                     |{1}   <a href=":artworks_url"><strong>:artworks_count</strong></a> dílo
                     |[2,4] <a href=":artworks_url"><strong>:artworks_count</strong></a> díla
                     |[5,*] <a href=":artworks_url"><strong>:artworks_count</strong></a> díl',
    'collections' => '{0}    v <strong>:collections_count</strong> kolekcích
                     |{1}    v <strong>:collections_count</strong> kolekcí
                     |[2, *] v <strong>:collections_count</strong> kolekcích',
    'views'       => '{0}   <strong>:view_count</strong> vidění
                     |{1}   <strong>:view_count</strong> vidění
                     |[2,4] <strong>:view_count</strong> vidění
                     |[5, *]<strong>:view_count</strong> vidění',

    'tags'              => 'tagy',
    'back-to-artists'   => 'seznam autorů',
    'alternative_names' => 'příp.',
    'places'            => 'působení',
    'links'             => 'externí odkazy',
    'relationships'     => 'vztahy',

    'artworks_by_artist' => 'díla autora',

    'button_show-all-artworks' => '{0} zobrazit <strong>0</strong> díl
                                  |{1} zobrazit <strong>:artworks_count</strong> dílo
                                  |[2,4] zobrazit všechny <strong>:artworks_count</strong> díla
                                  |[5, *] zobrazit všechny <strong>:artworks_count</strong> díl',

    'filter' => [
        'role' => 'role',
        'nationality' => 'příslušnost',
        'place' => 'místo',
        'title_generator' => [
            'first_letter' => 'začíná se na: :value',
            'role' => 'role: :value',
            'nationality' => 'příslušnost: :value',
            'place' => 'místo: :value',
            'years' => 'v letech :from — :to',
        ],
        'sort_by' => 'podle',
        'sorting' => [
            'items_with_images_count' => 'počtu díl s obrázkem',
            'name' => 'jména',
            'birth_year' => 'roku narození',
            'items_count' => 'počtu díl',
        ],
    ],

    'authors' => 'autoři',
    'authors_found' => 'nalezení autoři pro',
    'authors_counted' => 'autorů',
    'authors_none' => 'momentálně žádní autoři',
);
