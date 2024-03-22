<?php

namespace Tests\Harvest\Repositories;

use App\Harvest\Factories\EndpointFactory;
use App\Harvest\Repositories\MuseionItemRepository;
use App\SpiceHarvesterHarvest;
use Phpoaipmh\HttpAdapter\HttpAdapterInterface;
use Tests\TestCase;

class MuseionItemRepositoryTest extends TestCase
{
    protected HttpAdapterInterface $httpAdapterMock;

    protected MuseionItemRepository $itemRepository;

    public function setUp(): void
    {
        parent::setUp();

        $endpointFactoryMock = $this->getMockBuilder(EndpointFactory::class)
            ->onlyMethods(['createHttpAdapter'])
            ->getMock();
        $this->httpAdapterMock = $this->createMock(HttpAdapterInterface::class);
        $endpointFactoryMock->method('createHttpAdapter')->willReturn($this->httpAdapterMock);
        $this->itemRepository = new MuseionItemRepository($endpointFactoryMock);
        $this->harvest = SpiceHarvesterHarvest::factory()->make();
    }

    public function testGmuhkItem()
    {
        $xml = file_get_contents(__DIR__ . '/gmuhk_item.xml');
        $this->httpAdapterMock->method('request')->willReturn($xml);

        $rows = $this->itemRepository->getAll($this->harvest)->data;

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

    public function testMudbItem()
    {
        $xml = file_get_contents(__DIR__ . '/mudb_item.xml');
        $this->httpAdapterMock->method('request')->willReturn($xml);

        $rows = $this->itemRepository->getAll($this->harvest)->data;

        $rows = iterator_to_array($rows);
        $expected = [
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
        $this->assertEquals($expected, $rows[0]);
    }
}
