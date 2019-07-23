<?php

namespace Tests\Import\Importers;

use App\Authority;
use App\Harvest\Importers\ItemImporter;
use App\Harvest\Mappers\AuthorityItemMapper;
use App\Harvest\Mappers\ItemImageMapper;
use App\Harvest\Mappers\CollectionItemMapper;
use App\Harvest\Mappers\ItemMapper;
use App\Harvest\Result;
use App\Item;
use App\ItemImage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ItemImporterTest extends TestCase
{
    use DatabaseMigrations;

    public function testImport() {
        $row = $this->getData();
        $importer = $this->initImporter($row);
        $importer->import($row, $result = new Result());
    }

    public function testDeleteRelations() {
        $row = $this->getData();
        $importer = $this->initImporter($row);
        $item = factory(Item::class)->create(['id' => 'SVK:SNG.G_10044']);
        $image = factory(ItemImage::class)->make(['iipimg_url' => 'to_be_deleted']);
        $item->images()->save($image);

        $item->load('images');
        $this->assertTrue($item->images->contains(function (ItemImage $image) {
            return $image->iipimg_url === 'to_be_deleted';
        }));

        $item->load('images');
        $item = $importer->import($row, $result = new Result());

        $this->assertCount(2, $item->images);
        $this->assertFalse($item->images->contains(function (ItemImage $image) {
            return $image->iipimg_url === 'to_be_deleted';
        }));
    }

    public function testDetachRelations() {
        $row = $this->getData();
        $importer = $this->initImporter($row);
        $item = factory(Item::class)->create(['id' => 'SVK:SNG.G_10044']);
        factory(Authority::class)->create(['id' => 1922]);
        factory(Authority::class)->create(['id' => 10816]);
        $authority = factory(Authority::class)->create(['id' => 'to_be_detached']);
        $item->authorities()->attach($authority);

        $item->load('authorities');
        $this->assertTrue($item->authorities->contains(function (Authority $authority) {
            return $authority->id === 'to_be_detached';
        }));

        $item = $importer->import($row, $result = new Result());

        $this->assertCount(2, $item->authorities);
        $this->assertFalse($item->authorities->contains(function (Authority $authority) {
            return $authority->id === 'to_be_detached';
        }));
    }

    protected function getData() {
        // @todo faker
        return [
            'status' => [],
            'id' => ['SVK:SNG.G_10044'],
            'identifier' => [
                'SVK:SNG.G_10044',
                'http://www.webumenia.sk/oai-pmh/getimage/SVK:SNG.G_10044',
                'G 10044',
                'http://www.webumenia.sk:8080/webutils/resolveurl/SVK:SNG.G_10044/IMAGES',
//                'http://www.webumenia.sk:8080/webutils/resolveurl/SVK:SNG.G_10044/L2_WEB',
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
            'creator_role' => [
                'autor/author',
                'autor/author',
            ],
            'authorities' => [
                ['id' => ['urn:svk:psi:per:sng:0000001922']],
                ['id' => ['urn:svk:psi:per:sng:0000010816']],
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
            'provenance' => ['Slovenská národná galéria, SNG'],
            'created' => [
                '1760/1760',
                '18. storočie, polovica, 1760',
            ],
            'datestamp' => ['2017-08-28T14:00:23.769Z'],
            'images' => [
                [
                    'iipimg_url' => ['/SNGBA/X100/SNG--G_23--1_2--_2013_02_20_--L2_WEB.jp2'],
                ],
                [
                    'iipimg_url' => ['/SNGBA/X100/SNG--G_23--2vz_2--_2013_02_28_--L2_WEB.jp2'],
                ],
            ],
            'img_url' => 'http://www.webumenia.sk/oai-pmh/getimage/SVK:SNG.G_10044',
        ];
    }

    protected function initImporter(array $row) {
        $importer = new ItemImporter(
            $itemMapperMock = $this->createMock(ItemMapper::class),
            $itemImageMapperMock = $this->createMock(ItemImageMapper::class),
            $collectionItemMapperMock = $this->createMock(CollectionItemMapper::class),
            $authorityItemMapperMock = $this->createMock(AuthorityItemMapper::class)
        );
        $itemMapperMock
            ->expects($this->once())
            ->method('map')
            ->with($row)
            ->willReturn([
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
                'dating:sk' => '1760',
                'relationship_type:sk' => 'samostatné dielo',
                'related_work:sk' => null,
                'description:sk' => null,
                'work_level:sk' => null,
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
                'description:en' => null,
                'work_level:en' => null,
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
                'description:cs' => null,
                'work_level:cs' => null,
            ])
        ;
        $itemMapperMock
            ->expects($this->once())
            ->method('mapId')
            ->with($row)
            ->willReturn('SVK:SNG.G_10044');
        ;
        $itemImageMapperMock
            ->expects($this->exactly(2))
            ->method('map')
            ->withConsecutive(
                [$row['images'][0]],
                [$row['images'][1]]
            )
            ->willReturnOnConsecutiveCalls(
                [
                    'iipimg_url' => '/SNGBA/X100/SNG--G_23--1_2--_2013_02_20_--L2_WEB.jp2',
                ],
                [
                    'iipimg_url' => '/SNGBA/X100/SNG--G_23--2vz_2--_2013_02_28_--L2_WEB.jp2',
                ]
            )
        ;
        $authorityItemMapperMock
            ->expects($this->exactly(2))
            ->method('map')
            ->withConsecutive(
                [$row['authorities'][0]],
                [$row['authorities'][1]]
            )
            ->willReturnOnConsecutiveCalls(
                ['id' => 1922],
                ['id' => 10816]
            );

        return $importer;
    }
}