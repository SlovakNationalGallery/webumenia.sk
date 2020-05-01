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
        ];

        $mapped = $mapper->map($row);

        $expected = [
            'id' => 'SVK:SNG.G_10044',
            'identifier' => 'G 10044',
            'date_earliest' => '1760',
            'date_latest' => '1760',
            'author' => 'Daullé, Jean; Teniers, David',
            'related_work_order' => 0,
            'related_work_total' => 0,
            'img_url' => 'http://www.webumenia.sk/oai-pmh/getimage/SVK:SNG.G_10044',
            'title:sk' => 'Flámska rodina',
            'work_type:sk' => 'grafika, voľná',
            'technique:sk' => 'rytina',
            'medium:sk' => 'kartón, zahnedlý',
            'subject:sk' => null,
            'topic:sk' => 'figurálna kompozícia',
            'measurement:sk' => 'šírka 50.0 cm, šírka 47.6 cm, výška 39.0 cm, výška 37.0 cm, hĺbka 5.0 cm ()',
            'inscription:sk' => 'vpravo dole gravé J.Daullé..; vľavo dole peint Teniers',
            'place:sk' => null,
            'gallery:sk' => 'Slovenská národná galéria, SNG',
            'dating:sk' => '1760 (originál), 2019 (zväčšenina)',
            'relationship_type:sk' => 'samostatné dielo',
            'related_work:sk' => null,
            'work_level:sk' => null,
            'credit:sk' => 'Dar zo Zbierky Linea',
            'title:en' => 'Flemish family',
            'work_type:en' => null,
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
            'credit:cs' => 'Dar ze Sbírky Linea',
        ];
        $this->assertEquals($expected, $mapped);
    }
}