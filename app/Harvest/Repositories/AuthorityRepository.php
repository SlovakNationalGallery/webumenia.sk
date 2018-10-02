<?php

namespace App\Harvest\Repositories;

class AuthorityRepository extends AbstractRepository
{
    protected $xPathNamespaces = [
        'vp' => 'http://e-culture.multimedian.nl/ns/getty/vp#',
        'rdf' => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
    ];

    protected $fieldMap = [
        'identifier' => './/ns:identifier',
        'datestamp' => './/ns:datestamp',
        'birth_place' => './/vp:Birth_Place',
        'death_place' => './/vp:Death_Place',
        'type_organization' => './/vp:Record_Type_Organization',
        'biography' => './/vp:Biography_Text',
        'id' => './/vp:Subject/@rdf:about',
        'type' => './/vp:Record_Type',
        'name' => './/vp:Subject/@vp:labelPreferred',
        'sex' => './/vp:Sex',
        'birth_date' => './/vp:Birth_Date',
        'death_date' => './/vp:Death_Date',
        'roles' => './/vp:Role_ID',
        'names' => [
            null => './/vp:Term_Text',
            'name' => '.',
        ],
        'nationalities' => [
            null => './/vp:Preferred_Nationality',
            'id' => './@rdf:resource',
            'code' => './/vp:Nationality_Code',
        ],
        'events' => [
            null => './/vp:Non-Preferred_Event',
            'id' => './@rdf:resource',
            'event' => './/vp:Event_ID',
            'place' => './/vp:Place',
            'start_date' => './/vp:Start_Date',
            'end_date' => './/vp:End_Date',
        ],
        'relationships' => [
            null => './/vp:Associative_Relationship',
            'related_authority_id' => './/vp:Related_Subject_ID',
            'type' => './/vp:Relationship_Type',
        ],
    ];
}