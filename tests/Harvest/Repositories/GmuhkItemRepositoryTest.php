<?php

namespace Tests\Harvest\Repositories;

use App\Harvest\Factories\EndpointFactory;
use App\Harvest\Repositories\GmuhkItemRepository;
use App\SpiceHarvesterHarvest;
use Phpoaipmh\HttpAdapter\HttpAdapterInterface;
use Tests\TestCase;

class GmuhkItemRepositoryTest extends TestCase
{
    public function testAll() {
        $endpointFactoryMock = $this->getMockBuilder(EndpointFactory::class)
            ->setMethods(['createHttpAdapter'])
            ->getMock();
        $httpAdapterMock = $this->createMock(HttpAdapterInterface::class);
        $xml = file_get_contents(__DIR__ . '/gmuhk_item.xml');
        $httpAdapterMock->method('request')->willReturn($xml);
        $endpointFactoryMock->method('createHttpAdapter')->willReturn($httpAdapterMock);

        $itemRepository = new GmuhkItemRepository($endpointFactoryMock);

        $harvest = factory(SpiceHarvesterHarvest::class)->make();
        $rows = $itemRepository->getAll($harvest)->data;

        $rows = iterator_to_array($rows);
        $expected = [
            'id' => ['oai:khk.museion.cz:GMUHK~G0259'],
            'identifier' => [],
            'title' => ['Kuřák'],
            'description' => ['Sedící polopostava muže s knírem a vyržinkem v ústech a levou rukou položenou na roh stolu. Vzadu vystupuje v náznacích několik dalších postav za stoly. Ze šerosvitového pojetí celé scény, podané křížícími se sitěmi expresivně uvolněných šrafur, vystupuje i světelně akcentovaný obličej a ruka. Tožné s G 1007 a G 1342/4.'],
            'gallery' => ['Galerie moderního umění v Hradci Králové'],
            'datestamp' => ['2021-05-21T11:33:08Z'],
            'author' => ['Kubišta Bohumil'],
            'dating' => ['1907'],
            'date_earliest' => ['1907-01-01'],
            'date_latest' => ['1907-12-31'],
            'technique' => ['lept'],
            'medium' => ['papír'],
            'measurement' => ['vd.=160mm; sd.=162mm; v.=373mm; s.=303mm'],
            'image' => ['https://files.khk.museion.cz/GMUHK/Výtvarné umění/G/SUB_1/G0259/G0259-003_primary.jpg'],
        ];
        $this->assertEquals($expected, $rows[0]);
    }
}
