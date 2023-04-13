<?php

namespace Tests\Harvest\Importers;

use App\Authority;
use App\Harvest\Importers\ItemImporter;
use App\Harvest\Mappers\AuthorityItemMapper;
use App\Harvest\Mappers\BaseAuthorityMapper;
use App\Harvest\Mappers\ItemImageMapper;
use App\Harvest\Mappers\CollectionItemMapper;
use App\Harvest\Mappers\ItemMapper;
use App\Harvest\Progress;
use App\Item;
use App\ItemImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Harvest\FakeRecordFactory;
use Tests\TestCase;
use Tests\WithoutSearchIndexing;

class ItemImporterTest extends TestCase
{
    private ItemImporter $importer;

    use RefreshDatabase;
    use WithoutSearchIndexing;

    protected function setUp(): void
    {
        parent::setUp();

        $this->importer = new ItemImporter(
            app(ItemMapper::class),
            app(ItemImageMapper::class),
            app(CollectionItemMapper::class),
            app(AuthorityItemMapper::class),
            app(BaseAuthorityMapper::class)
        );
    }

    public function testKeepImages()
    {
        $item = Item::factory()->create(['id' => 'SVK:SNG.G_10044']);
        $image = ItemImage::factory()->make(['iipimg_url' => 'to_keep']);
        $item->images()->save($image);

        $item->load('images');
        $this->assertTrue(
            $item->images->contains(function (ItemImage $image) {
                return $image->iipimg_url === 'to_keep';
            })
        );

        $item->load('images');

        $row = FakeRecordFactory::buildItem([
            'images' => [
                [
                    'iipimg_url' => ['/SNGBA/X100/SNG--G_23--1_2--_2013_02_20_--L2_WEB.jp2'],
                ],
                [
                    'iipimg_url' => ['/SNGBA/X100/SNG--G_23--2vz_2--_2013_02_28_--L2_WEB.jp2'],
                ],
            ],
        ]);
        $item = $this->importer->import($row, $result = new Progress());

        $this->assertCount(3, $item->images);
        $this->assertTrue(
            $item->images->contains(function (ItemImage $image) {
                return $image->iipimg_url === 'to_keep';
            })
        );
    }

    public function testDetachRelations()
    {
        $item = Item::factory()->create(['id' => 'SVK:SNG.G_10044']);
        Authority::factory()->create(['id' => 1922]);
        $authority = Authority::factory()->create(['id' => 'to_be_detached']);
        $item->authorities()->attach($authority);

        $item->load('authorities');
        $this->assertTrue(
            $item->authorities->contains(function (Authority $authority) {
                return $authority->id === 'to_be_detached';
            })
        );

        $row = FakeRecordFactory::buildItem();
        $item = $this->importer->import($row, $result = new Progress());

        $this->assertFalse(
            $item->authorities->contains(function (Authority $authority) {
                return $authority->id === 'to_be_detached';
            })
        );
    }

    public function testImportAuthorityPivotData()
    {
        Authority::factory()->create(['id' => 1922]);

        $row = FakeRecordFactory::buildItem([
            'authorities' => [
                [
                    'id' => ['urn:svk:psi:per:sng:0000001922'],
                    'name' => ['Daullé, Jean'],
                    'role' => ['autor/author'],
                ],
            ],
        ]);
        $item = $this->importer->import($row, $result = new Progress());

        $this->assertCount(1, $item->authorities);
        $author = $item->authorities->first(function (Authority $authority) {
            return $authority->id == 1922;
        });
        $this->assertEquals('autor/author', $author->pivot->role);
    }

    public function testImportNonExistingAuthority()
    {
        // testing for specific bug where records in pivot table
        // were repeatedly created by harvester and therefore
        // after saving related model, the relation was multiplied

        $authority = Authority::factory()->make(['id' => 1922]);

        $row = FakeRecordFactory::buildItem();

        for ($i = 2; $i--; ) {
            $item = $this->importer->import($row, new Progress());
            $count = $item->authorities->count();
            $this->assertEquals(0, $count);
        }

        $authority->save();

        $item = $this->importer->import($row, new Progress());
        $count = $item->authorities->count();
        $this->assertEquals(1, $count);
    }
}
