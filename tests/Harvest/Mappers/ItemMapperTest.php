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
            'type' => [
                [
                    'lang' => ['sk'],
                    'type' => ['grafika, voľná'],
                ],
                [
                    'lang' => [],
                    'type' => ['DEF'],
                ],
                [
                    'lang' => [],
                    'type' => ['originál'],
                ],
                [
                    'lang' => [],
                    'type' => ['Image'],
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
            'creator' => [
                'urn:svk:psi:per:sng:0000001922',
                'Daullé, Jean',
                'urn:svk:psi:per:sng:0000010816',
                'Teniers, David',
            ],
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
            'provenance' => ['Slovenská národná galéria, SNG'],
            'created' => [
                '1760/1760',
                '18. storočie, polovica, 1760',
            ],
        ];

        $mapped = $mapper->map($row);

        $expected = [
            'id' => 'SVK:SNG.G_10044',
            'identifier' => 'G 10044',
            'title' => 'Flámska rodina',
            'work_type' => 'grafika, voľná',
            'topic' => 'figurálna kompozícia',
            'measurement' => 'šírka 50.0 cm, šírka 47.6 cm, výška 39.0 cm, výška 37.0 cm, hĺbka 5.0 cm ()',
            'dating' => '1760',
            'date_earliest' => '1760',
            'date_latest' => '1760',
            'medium' => 'kartón, zahnedlý',
            'technique' => 'rytina',
            'inscription' => 'vpravo dole gravé J.Daullé..; vľavo dole peint Teniers',
            'relationship_type' => 'samostatné dielo',
            'gallery' => 'Slovenská národná galéria, SNG',
            'author' => 'Daullé, Jean; Teniers, David',
            'description' => '',
            'work_level' => '',
            'item_type' => '',
            'subject' => '',
            'place' => '',
            'related_work' => '',
            'related_work_order' => 0,
            'related_work_total' => 0,
            'featured' => false,
        ];
        $this->assertEquals($expected, $mapped);
    }
}