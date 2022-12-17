<?php

namespace Tests\Harvest\Mappers;

use App\Harvest\Mappers\ItemMapper;
use Tests\TestCase;

class ItemMapperTest extends TestCase
{
    public function testMap() {
        $mapper = new ItemMapper();
        $row = [
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
                ]
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
            'rights' => [
                '1',
                'publikovať/public',
            ],
            'description' => [
                'vpravo dole gravé J.Daullé..',
                'vľavo dole peint Teniers',
            ],
            'extent' => ['šírka 50.0 cm, šírka 47.6 cm, výška 39.0 cm, výška 37.0 cm, hĺbka 5.0 cm ()'],
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
        ];

        $mapped = $mapper->map($row);

        $expected = [
            'id' => 'SVK:SNG.G_10044',
            'identifier' => 'G 10044',
            'date_earliest' => '1760',
            'date_latest' => '1760',
            'author' => 'Daullé, Jean',
            'related_work_order' => 0,
            'related_work_total' => 0,
            'img_url' => 'http://www.webumenia.sk/oai-pmh/getimage/SVK:SNG.G_10044',
            'title:sk' => 'Flámska rodina',
            'work_type:sk' => 'grafika, voľná',
            'object_type:sk' => 'fotografia; mapa',
            'technique:sk' => 'rytina',
            'medium:sk' => 'kartón, zahnedlý',
            'subject:sk' => null,
            'topic:sk' => 'figurálna kompozícia',
            'measurement:sk' => 'šírka 50.0 cm, šírka 47.6 cm, výška 39.0 cm, výška 37.0 cm, hĺbka 5.0 cm ()',
            'inscription:sk' => 'vpravo dole gravé J.Daullé..; vľavo dole peint Teniers',
            'place:sk' => null,
            'gallery:sk' => 'Slovenská národná galéria, SNG',
            'dating:sk' => '18. storočie, polovica, 1760 (originál); 21. storočie, 1. polovica, 2019 (zväčšenina)',
            'relationship_type:sk' => 'samostatné dielo',
            'related_work:sk' => null,
            'work_level:sk' => null,
            'credit:sk' => 'Dar zo Zbierky Linea',
            'title:en' => 'Flemish family',
            'work_type:en' => null,
            'object_type:en' => 'map',
            'technique:en' => 'engraving',
            'medium:en' => null,
            'subject:en' => null,
            'topic:en' => 'figurative composition',
            'measurement:en' => null,
            'inscription:en' => null,
            'place:en' => null,
            'gallery:en' => null,
            'dating:en' => null,
            'relationship_type:en' => null,
            'related_work:en' => null,
            'work_level:en' => null,
            'credit:en' => 'Donation from the Linea Collection',
            'title:cs' => null,
            'work_type:cs' => null,
            'object_type:cs' => null,
            'technique:cs' => null,
            'medium:cs' => null,
            'subject:cs' => null,
            'topic:cs' => 'figurální',
            'measurement:cs' => null,
            'inscription:cs' => null,
            'place:cs' => null,
            'gallery:cs' => null,
            'dating:cs' => null,
            'relationship_type:cs' => null,
            'related_work:cs' => null,
            'work_level:cs' => null,
            'contributor' => 'Čičo, Martin',
            'credit:cs' => 'Dar ze Sbírky Linea',
        ];
        $this->assertEquals($expected, $mapped);
    }

    public function testMapRelatedWork_NoData()
    {
        $mapper = new ItemMapper();
        $row = [];

        $this->assertEquals(null, $mapper->mapRelationshipType($row, 'sk'));
        $this->assertEquals(null, $mapper->mapRelatedWork($row, 'sk'));
        $this->assertEquals(null, $mapper->mapRelatedWorkOrder($row));
        $this->assertEquals(null, $mapper->mapRelatedWorkTotal($row));
    }

    public function testMapRelatedWork_NoRelation()
    {
        $mapper = new ItemMapper();
        $row = [
            'relation_isPartOf' => ['samostatné dielo'],
        ];

        $this->assertEquals('samostatné dielo', $mapper->mapRelationshipType($row, 'sk'));
        $this->assertEquals(null, $mapper->mapRelatedWork($row, 'sk'));
        $this->assertEquals(null, $mapper->mapRelatedWorkOrder($row));
        $this->assertEquals(null, $mapper->mapRelatedWorkTotal($row));
    }

    public function testMapRelatedWork_NoRelationshipType()
    {
        $mapper = new ItemMapper();
        $row = [
            'relation_isPartOf' => [':Bez uvedenia názvu (HAPPSOC, Stano Filko 1965/69) (15/15)'],
        ];

        $this->assertEquals(null, $mapper->mapRelationshipType($row, 'sk'));
        $this->assertEquals('Bez uvedenia názvu (HAPPSOC, Stano Filko 1965/69)', $mapper->mapRelatedWork($row, 'sk'));
        $this->assertEquals(15, $mapper->mapRelatedWorkOrder($row));
        $this->assertEquals(15, $mapper->mapRelatedWorkTotal($row));
    }

    public function testMapRelatedWork_NoOrderNoTotal()
    {
        $mapper = new ItemMapper();
        $row = [
            'relation_isPartOf' => ['zo skicára:11. Náčrtník(/59)'],
        ];

        $this->assertEquals('zo skicára', $mapper->mapRelationshipType($row, 'sk'));
        $this->assertEquals('11. Náčrtník', $mapper->mapRelatedWork($row, 'sk'));
        $this->assertEquals(null, $mapper->mapRelatedWorkOrder($row));
        $this->assertEquals(59, $mapper->mapRelatedWorkTotal($row));
    }

    public function testMapRelatedWork_NoOrderWithTotal()
    {
        $mapper = new ItemMapper();
        $row = [
            'relation_isPartOf' => ['z cyklu:Malá premena'],
        ];

        $this->assertEquals('z cyklu', $mapper->mapRelationshipType($row, 'sk'));
        $this->assertEquals('Malá premena', $mapper->mapRelatedWork($row, 'sk'));
        $this->assertEquals(null, $mapper->mapRelatedWorkOrder($row));
        $this->assertEquals(null, $mapper->mapRelatedWorkTotal($row));
    }

    public function testMapRelatedWork_WithOrderNoTotal()
    {
        $mapper = new ItemMapper();
        $row = [
            'relation_isPartOf' => ['z cyklu:Pocta Karolovi Plickovi(4/)'],
        ];

        $this->assertEquals('z cyklu', $mapper->mapRelationshipType($row, 'sk'));
        $this->assertEquals('Pocta Karolovi Plickovi', $mapper->mapRelatedWork($row, 'sk'));
        $this->assertEquals(4, $mapper->mapRelatedWorkOrder($row));
        $this->assertEquals(null, $mapper->mapRelatedWorkTotal($row));
    }

    public function testMapRelatedWork_WithOrderWithTotal()
    {
        $mapper = new ItemMapper();
        $row = [
            'relation_isPartOf' => ['z albumu:Album I.(1/44)'],
        ];

        $this->assertEquals('z albumu', $mapper->mapRelationshipType($row, 'sk'));
        $this->assertEquals('Album I.', $mapper->mapRelatedWork($row, 'sk'));
        $this->assertEquals(1, $mapper->mapRelatedWorkOrder($row));
        $this->assertEquals(44, $mapper->mapRelatedWorkTotal($row));
    }
}