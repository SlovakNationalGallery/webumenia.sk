<?php

namespace Tests\Harvest\Mappers;

use App\Harvest\Mappers\MudbItemMapper;
use Tests\TestCase;

class MudbItemMapperTest extends TestCase
{
    public function testMap()
    {
        $mapper = app(MudbItemMapper::class);
        $row = [
            'id' => ['oai:museion-online.cz:MUDB~publikacePredmetu~00288'],
            'identifier' => ['K_288'],
            'title' => ['Pro mír a život'],
            'gallery' => ['Muzeum umění a designu Benešov'],
            'datestamp' => ['2023-10-23T20:29:16Z'],
            'author' => ['Jan Hejtmánek'],
            'dating' => ['1983'],
            'date_earliest' => ['1983-01-01'],
            'date_latest' => ['1983-12-31'],
            'technique' => ['dřevořez'],
            'medium' => ['papír'],
            'measurement' => ['délka=13cm; šířka=9cm'],
            'image' => ['https://media.museion.cz/MUDB/Kresba/_/SUB_1/00288/PF_K288_primary.jpg'],
            'work_type' => ['publikacePredmetu:MUDB:K:K'],
        ];

        $mapped = $mapper->map($row);

        $expected = [
            'id' => 'CZE:MUDB.K_288',
            'identifier' => 'K_288',
            'title:cs' => 'Pro mír a život',
            'title:sk' => 'Pro mír a život',
            'title:en' => null,
            'gallery:cs' => 'Muzeum umění a designu Benešov',
            'gallery:sk' => 'Muzeum umění a designu Benešov',
            'gallery:en' => null,
            'author' => 'Jan Hejtmánek',
            'dating:sk' => '1983',
            'dating:en' => null,
            'dating:cs' => '1983',
            'date_earliest' => 1983,
            'date_latest' => 1983,
            'technique:sk' => 'drevoryt',
            'technique:en' => 'woodcut',
            'technique:cs' => 'dřevořez',
            'medium:cs' => 'papír',
            'medium:sk' => 'papier',
            'medium:en' => 'paper',
            'measurement:cs' => 'délka 13cm; šířka 9cm',
            'measurement:sk' => 'délka 13cm; šířka 9cm',
            'measurement:en' => null,
            'work_type:sk' => 'kresba',
            'work_type:en' => 'drawing',
            'work_type:cs' => 'kresba',
        ];
        $this->assertEquals($expected, $mapped);
    }
}
