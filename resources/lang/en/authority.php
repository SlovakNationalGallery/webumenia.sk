<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Language Lines for autor.blade.php template
    |--------------------------------------------------------------------------
    */

    'artworks'    => '{0}    <a href=":artworks_url"><strong>:artworks_count</strong></a> artworks
                     |{1}    <a href=":artworks_url"><strong>:artworks_count</strong></a> artwork
                     |[2,*]<a href=":artworks_url"><strong>:artworks_count</strong></a> artworks',
    'collections' => '{0}    in <strong>:collections_count</strong> collections
                     |{1}    in <strong>:collections_count</strong> collection
                     |[2,*]in <strong>:collections_count</strong> collections',
    'views'       => '{0}    <strong>:view_count</strong> views
                     |{1}    <strong>:view_count</strong> view
                     |[2,*]<strong>:view_count</strong> views',

    'tags'              => 'tags',
    'back-to-artists'   => 'back to artists page',
    'alternative_names' => 'alternatively',
    'places'            => 'has been active in',
    'links'             => 'external links',
    'relationships'     => 'relationships',

    'artworks_by_artist' => 'artworks by this artist',

    'button_show-all-artworks' => '{0}    show <strong>0</strong> artworks
                                  |{1}    show <strong>1</strong> artwork
                                  |[2,*]show all <strong>:artworks_count</strong> artworks',

    'filter' => [
        'role' => 'role',
        'nationality' => 'nationality',
        'place' => 'location',
        'title_generator' => [
            'first_letter' => 'first letter: :value',
            'role' => 'role: :value',
            'nationality' => 'nationality: :value',
            'place' => 'location: :value',
            'years' => 'from :from — :to',
        ],
        'sort_by' => 'sort by',
        'sorting' => [
            'items_with_images_count' => 'artworks with image count',
            'name' => 'name',
            'birth_year' => 'birth year',
            'items_count' => 'artworks count',
        ],
    ],

    'authors'             => 'artists',
    'authors_found'       => 'artists found for',
    'authors_counted'     => 'artists',
    'authors_none'        => 'currently no artists',
    'roles'               => 'roles',
    'role'=>[
        "author" => "author",
        "after" => "after",
        "atelier" => "atelier",
        "circle" => "circle of",
        "copyist" => "copyist of",
        "draft" => "draft by",
        "drawer" => "draftsman",
        "engraver" => "engraver",
        "epigone" => "epigone of",
        "follower" => "follower of",
        "former" => "formerly attributed to",
        "graphic" => "graphic artist",
        "modifier" => "modified by",
        "office" => "office of",
        "original" => "original by",
        "printer" => "printer",
        "probably" => "probably by",
        "probablyAfter" => "probably after",
        "probablyCircle" => "probably circle of",
        "probablyDrawer" => "probable draftsman",
        "probablyEngraver" => "probable engraver",
        "probablyPrinter" => "probable printer",
        "probablyWorkshop" => "probably workshop of",
        "producer" => "producer",
        "publisher" => "publisher",
        "restorer" => "restorer",
        "workshop" => "workshop of",
        "concept" => "concept by",
        "photograph" => "photographer",
    ]
);
