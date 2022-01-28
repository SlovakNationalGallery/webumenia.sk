<?php

namespace Tests\Harvest\Repositories;

use App\Harvest\Factories\EndpointFactory;
use App\Harvest\Repositories\ItemRepository;
use App\SpiceHarvesterHarvest;
use Phpoaipmh\HttpAdapter\HttpAdapterInterface;
use Tests\TestCase;

class ItemRepositoryTest extends TestCase
{
    public function testRows() {
        $endpointFactoryMock = $this->getMockBuilder(EndpointFactory::class)
            ->setMethods(['createHttpAdapter'])
            ->getMock();
        $httpAdapterMock = $this->createMock(HttpAdapterInterface::class);
        $authorityXml = file_get_contents(__DIR__ . '/item.xml');
        $httpAdapterMock->method('request')->willReturn($authorityXml);
        $endpointFactoryMock->method('createHttpAdapter')->willReturn($httpAdapterMock);

        $itemRepository = new ItemRepository($endpointFactoryMock);

        $harvest = factory(SpiceHarvesterHarvest::class)->make(['base_url' => true]);
        $rows = $itemRepository->getAll($harvest)->data;

        $rows = iterator_to_array($rows);
        $this->assertCount(1, $rows);
        $expected = [
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
            'creator' => [
                'urn:svk:psi:per:sng:0000001922',
                'Daullé, Jean',
                'urn:svk:psi:per:sng:0000010816',
                'Teniers, David',
            ],
            'authorities' => [
                [
                    'id' => ['urn:svk:psi:per:sng:0000001922'],
                    'role' => ['autor/author'],
                ],
                [
                    'id' => ['urn:svk:psi:per:sng:0000010816'],
                    'role' => ['iné/other'],
                ],
            ],
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
                '18. storočie, polovica, 1760',
            ],
            'datestamp' => ['2017-08-28T14:00:23.769Z'],
            'contributor' => ['Čičo, Martin'],
        ];
        $this->assertEquals($expected, $rows[0]);
    }
}
