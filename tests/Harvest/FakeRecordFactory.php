<?php

namespace Tests\Harvest;

class FakeRecordFactory
{
    public static function buildItem($overrides = [])
    {
        return array_merge(
            [
                'status' => [],
                'id' => ['SVK:SNG.G_10044'],
                'identifier' => [
                    'SVK:SNG.G_10044',
                    'http://www.webumenia.sk/oai-pmh/getimage/SVK:SNG.G_10044',
                    'G 10044',
                    'http://www.webumenia.sk:8080/webutils/resolveurl/SVK:SNG.G_10044/IMAGES',
                    'http://www.webumenia.sk:8080/webutils/resolveurl/SVK:SNG.G_10044/L2_WEB',
                ],
                'title_translated' => [
                    [
                        'lang' => ['en'],
                        'title_translated' => ['Flemish family'],
                    ],
                ],
                'work_type' => [
                    [
                        'lang' => ['sk'],
                        'work_type' => ['grafika, voľná'],
                    ],
                    [
                        'lang' => [],
                        'work_type' => ['DEF'],
                    ],
                    [
                        'lang' => [],
                        'work_type' => ['originál'],
                    ],
                    [
                        'lang' => [],
                        'work_type' => ['Image'],
                    ],
                ],
                'object_type' => [
                    [
                        'lang' => ['sk'],
                        'object_type' => ['fotografia'],
                    ],
                    [
                        'lang' => ['sk'],
                        'object_type' => ['mapa'],
                    ],
                    [
                        'lang' => ['en'],
                        'object_type' => ['map'],
                    ],
                ],
                'format' => [
                    [
                        'lang' => ['en'],
                        'format' => ['engraving'],
                    ],
                    [
                        'lang' => ['sk'],
                        'format' => ['rytina'],
                    ],
                ],
                'format_medium' => [
                    [
                        'lang' => ['sk'],
                        'format_medium' => ['kartón, zahnedlý'],
                    ],
                ],
                'subject' => [
                    [
                        'lang' => ['en'],
                        'subject' => ['figurative composition'],
                    ],
                    [
                        'lang' => ['sk'],
                        'subject' => ['figurálna kompozícia'],
                    ],
                    [
                        'lang' => ['cs'],
                        'subject' => ['figurální'],
                    ],
                ],
                'title' => ['Flámska rodina'],
                'subject_place' => [],
                'relation_isPartOf' => ['samostatné dielo'],
                'creator_role' => [],
                'rights' => ['1', 'publikovať/public'],
                'description' => ['vpravo dole gravé J.Daullé..', 'vľavo dole peint Teniers'],
                'extent' => [
                    'šírka 50.0 cm, šírka 47.6 cm, výška 39.0 cm, výška 37.0 cm, hĺbka 5.0 cm ()',
                ],
                'gallery' => ['Slovenská národná galéria, SNG'],
                'credit' => [
                    [
                        'lang' => ['sk'],
                        'credit' => ['Dar zo Zbierky Linea'],
                    ],
                    [
                        'lang' => ['en'],
                        'credit' => ['Donation from the Linea Collection'],
                    ],
                    [
                        'lang' => ['cs'],
                        'credit' => ['Dar ze Sbírky Linea'],
                    ],
                ],
                'created' => [
                    '1760/1760',
                    '18. storočie, polovica, 1760 (originál)',
                    '1860/1860',
                    '21. storočie, 1. polovica, 2019 (zväčšenina)',
                ],
                'contributor' => ['Čičo, Martin'],
                'authorities' => [
                    [
                        'id' => ['urn:svk:psi:per:sng:0000001922'],
                        'name' => ['Daullé, Jean'],
                        'role' => ['autor/author'],
                    ],
                ],
            ],
            $overrides
        );
    }
    public static function buildAuthority($overrides = [])
    {
        return array_merge(
            [
                'identifier' => ['954'],
                'birth_place' => ['Považská Bystrica'],
                'death_place' => ['Bratislava'],
                'type_organization' => ['Zbierkotvorná galéria'],
                'biography' => ['AUTOR: Blühová Irena (ZNÁMY)'],
                'id' => ['urn:svk:psi:per:sng:0000000954'],
                'type' => ['Person'],
                'name' => ['Blühová, Irena'],
                'sex' => ['Female'],
                'birth_date' => ['02.03.1904'],
                'death_date' => ['30.11.1991'],
                'roles' => ['fotograf/photographer'],
                'names' => [
                    [
                        'name' => ['I. B.'],
                    ],
                ],
                'nationalities' => [
                    [
                        'id' => ['urn:svk:psi:per:sng:0000012277'],
                        'code' => ['Slovensko'],
                    ],
                ],
                'events' => [
                    [
                        'id' => ['1000166'],
                        'event' => ['štúdium/study'],
                        'place' => ['Berlín'],
                        'start_date' => ['1931'],
                        'end_date' => ['1932'],
                    ],
                ],
                'relationships' => [
                    [
                        'type' => [
                            'študent (osoba - inštitúcia)/student at (person to institution)',
                        ],
                        'related_authority_id' => ['urn:svk:psi:per:sng:0001000162'],
                    ],
                    [
                        'type' => ['člen/member'],
                        'related_authority_id' => ['urn:svk:psi:per:sng:0001000168'],
                    ],
                    [
                        'type' => ['partner/partner'],
                        'related_authority_id' => ['urn:svk:psi:per:sng:0000011680'],
                    ],
                ],
                'datestamp' => ['2015-02-16T22:55:34Z'],
            ],
            $overrides
        );
    }
}
