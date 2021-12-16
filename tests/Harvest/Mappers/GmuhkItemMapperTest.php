<?php

namespace Tests\Harvest\Mappers;

use App\Harvest\Mappers\GmuhkItemMapper;
use Tests\TestCase;

class GmuhkItemMapperTest extends TestCase
{
    public function testMap() {
        $mapper = new GmuhkItemMapper(app('translator'));
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
            'work_type' => ['publikacePredmetu:GMUHK:151:G'],
        ];

        $mapped = $mapper->map($row);

        $expected = [
            'id' => 'CZE:GMUHK.G_259',
            'identifier' => 'G 259',
            'author' => 'Kubišta Bohumil',
            'title:sk' => 'Kuřák',
            'title:en' => 'Kuřák',
            'title:cs' => 'Kuřák',
            'dating:sk' => '1907',
            'dating:en' => '1907',
            'dating:cs' => '1907',
            'date_earliest' => '1907',
            'date_latest' => '1907',
            'technique:sk' => 'lept',
            'technique:en' => 'lept',
            'technique:cs' => 'lept',
            'medium:sk' => 'papír',
            'medium:en' => 'papír',
            'medium:cs' => 'papír',
            'measurement:sk' => 'výška grafickej dosky 160mm; šírka grafickej plochy 162mm; celková výška/dĺžka 373mm; šírka 303mm',
            'measurement:en' => 'height of the printing plate 160mm; width of the printing plate 162mm; overall height/length 373mm; width 303mm',
            'measurement:cs' => 'výška grafické desky 160mm; šířka grafické desky 162mm; celková výška/délka 373mm; šířka 303mm',
            'gallery:sk' => 'Galerie moderního umění v Hradci Králové',
            'gallery:en' => 'Galerie moderního umění v Hradci Králové',
            'gallery:cs' => 'Galerie moderního umění v Hradci Králové',
            'work_type:sk' => 'grafika',
            'work_type:en' => 'graphics',
            'work_type:cs' => 'grafika',
        ];
        $this->assertEquals($expected, $mapped);
    }
}