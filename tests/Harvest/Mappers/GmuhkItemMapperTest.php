<?php

namespace Tests\Harvest\Gmuhk\Mappers;

use App\Harvest\Mappers\GmuhkItemMapper;
use Tests\TestCase;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;

class ItemMapperTest extends TestCase
{
    public function testMap() {
        $mapper = new GmuhkItemMapper(app('translator'));
        $row = [
            'id' => ['oai:khk.museion.cz:GMUHK~G0259'],
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
            'description' => ['Sedící polopostava muže s knírem a vyržinkem v ústech a levou rukou položenou na roh stolu. Vzadu vystupuje v náznacích několik dalších postav za stoly. Ze šerosvitového pojetí celé scény, podané křížícími se sitěmi expresivně uvolněných šrafur, vystupuje i světelně akcentovaný obličej a ruka. Tožné s G 1007 a G 1342/4.'],
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
            'description:sk' => 'Sedící polopostava muže s knírem a vyržinkem v ústech a levou rukou položenou na roh stolu. Vzadu vystupuje v náznacích několik dalších postav za stoly. Ze šerosvitového pojetí celé scény, podané křížícími se sitěmi expresivně uvolněných šrafur, vystupuje i světelně akcentovaný obličej a ruka. Tožné s G 1007 a G 1342/4.',
            'description:en' => 'Sedící polopostava muže s knírem a vyržinkem v ústech a levou rukou položenou na roh stolu. Vzadu vystupuje v náznacích několik dalších postav za stoly. Ze šerosvitového pojetí celé scény, podané křížícími se sitěmi expresivně uvolněných šrafur, vystupuje i světelně akcentovaný obličej a ruka. Tožné s G 1007 a G 1342/4.',
            'description:cs' => 'Sedící polopostava muže s knírem a vyržinkem v ústech a levou rukou položenou na roh stolu. Vzadu vystupuje v náznacích několik dalších postav za stoly. Ze šerosvitového pojetí celé scény, podané křížícími se sitěmi expresivně uvolněných šrafur, vystupuje i světelně akcentovaný obličej a ruka. Tožné s G 1007 a G 1342/4.',
        ];
        $this->assertEquals($expected, $mapped);
    }
}