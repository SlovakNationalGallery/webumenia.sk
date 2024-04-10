<?php

namespace Tests\Harvest\Mappers;

use App\Harvest\Mappers\GmuhkItemMapper;
use Tests\TestCase;

class GmuhkItemMapperTest extends TestCase
{
    public function testMap()
    {
        $mapper = new GmuhkItemMapper();
        $row = [
            'id' => ['oai:khk.museion.cz:GMUHK~publikacePredmetu~G0259'],
            'identifier' => ['G 259'],
            'author' => ['Kubišta Bohumil'],
            'title' => ['Kuřák'],
            'dating' => ['1907'],
            'date_earliest' => ['1907-01-01'],
            'date_latest' => ['1907-12-31'],
            'technique' => ['lept'],
            'medium' => ['papír'],
            'measurement' => ['vd.=160mm; sd.=162mm; v.=373mm; s.=303mm'],
            'gallery' => ['Galerie moderního umění v Hradci Králové'],
            'work_type' => ['publikacePredmetu:GMUHK:151:G:Gr'],
        ];

        $mapped = $mapper->map($row);

        $expected = [
            'id' => 'CZE:GMUHK.G_259',
            'identifier' => 'G 259',
            'author' => 'Kubišta Bohumil',
            'title:sk' => 'Kuřák',
            'title:en' => null,
            'title:cs' => 'Kuřák',
            'dating:sk' => '1907',
            'dating:en' => null,
            'dating:cs' => '1907',
            'date_earliest' => 1907,
            'date_latest' => 1907,
            'technique:sk' => 'lept',
            'technique:en' => 'etching',
            'technique:cs' => 'lept',
            'medium:sk' => 'papier',
            'medium:en' => 'paper',
            'medium:cs' => 'papír',
            'measurement:sk' =>
                'výška grafickej dosky 160mm; šírka grafickej plochy 162mm; celková výška/dĺžka 373mm; šírka 303mm',
            'measurement:en' =>
                'height of the printing plate 160mm; width of the printing plate 162mm; overall height/length 373mm; width 303mm',
            'measurement:cs' =>
                'výška grafické desky 160mm; šířka grafické desky 162mm; celková výška/délka 373mm; šířka 303mm',
            'gallery:sk' => 'Galerie moderního umění v Hradci Králové',
            'gallery:en' => null,
            'gallery:cs' => 'Galerie moderního umění v Hradci Králové',
            'work_type:sk' => 'grafika',
            'work_type:en' => 'graphics',
            'work_type:cs' => 'grafika',
            'credit:sk' => null,
            'credit:en' => null,
            'credit:cs' => null,
        ];
        $this->assertEquals($expected, $mapped);
    }

    public function testMapWorkTypeFallback()
    {
        $mapper = new GmuhkItemMapper();
        $row = [
            'id' => ['oai:khk.museion.cz:GMUHK~publikacePredmetu~G0259'],
            'work_type' => ['publikacePredmetu:GMUHK:151'],
        ];

        $this->assertEquals('grafika', $mapper->mapWorkType($row, 'sk'));
        $this->assertEquals('grafika', $mapper->mapWorkType($row, 'cs'));
        $this->assertEquals('graphics', $mapper->mapWorkType($row, 'en'));
    }

    public function testMapCredit_Skt()
    {
        $mapper = new GmuhkItemMapper();
        $row = [
            'identifier' => ['SKT 372'],
        ];

        $this->assertEquals('Zbierka Karla Tutscha', $mapper->mapCredit($row, 'sk'));
        $this->assertEquals('Sbírka Karla Tutsche', $mapper->mapCredit($row, 'cs'));
        $this->assertEquals('Karel Tutsch Collection', $mapper->mapCredit($row, 'en'));
    }
}
