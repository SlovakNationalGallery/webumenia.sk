<?php

namespace Tests\Harvest\Repositories;

use App\Harvest\Factories\EndpointFactory;
use App\Harvest\Repositories\GmuhkItemRepository;
use App\SpiceHarvesterHarvest;
use Phpoaipmh\HttpAdapter\HttpAdapterInterface;
use Tests\TestCase;

class GmuhkItemRepositoryTest extends TestCase
{
    public function testAll()
    {
        $endpointFactoryMock = $this->getMockBuilder(EndpointFactory::class)
            ->onlyMethods(['createHttpAdapter'])
            ->getMock();
        $httpAdapterMock = $this->createMock(HttpAdapterInterface::class);
        $xml = file_get_contents(__DIR__ . '/gmuhk_item.xml');
        $httpAdapterMock->method('request')->willReturn($xml);
        $endpointFactoryMock->method('createHttpAdapter')->willReturn($httpAdapterMock);

        $itemRepository = new GmuhkItemRepository($endpointFactoryMock);

        $harvest = SpiceHarvesterHarvest::factory()->make();
        $rows = $itemRepository->getAll($harvest)->data;

        $rows = iterator_to_array($rows);
        $expected = [
            'id' => ['oai:khk.museion.cz:GMUHK~publikacePredmetu~G0259'],
            'identifier' => ['G 259'],
            'title' => ['Kuřák'],
            'gallery' => ['Galerie moderního umění v Hradci Králové'],
            'datestamp' => ['2021-05-21T11:33:08Z'],
            'author' => ['Kubišta Bohumil'],
            'dating' => ['1907'],
            'date_earliest' => ['1907-01-01'],
            'date_latest' => ['1907-12-31'],
            'technique' => ['lept'],
            'medium' => ['papír'],
            'measurement' => ['vd.=160mm; sd.=162mm; v.=373mm; s.=303mm'],
            'image' => [
                'https://files.khk.museion.cz/GMUHK/Výtvarné umění/G/SUB_1/G0259/G0259-003_primary.jpg',
            ],
            'work_type' => ['publikacePredmetu:GMUHK:151:G'],
        ];
        $this->assertEquals($expected, $rows[0]);
    }
}
