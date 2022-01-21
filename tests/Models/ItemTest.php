<?php

namespace Tests\Models;

use App\Authority;
use App\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    public function testFreeFromDateLatest() {
        $item = factory(Item::class)->make([
            'gallery' => 'Slovenská národná galéria, SNG',
            'date_latest' => 2000,
        ]);

        $this->assertEquals((new \DateTime('2131-01-01'))->getTimestamp(), $item->freeFrom());
    }

    public function testFreeFromAuthorityDeathYear() {
        $item = $this->createFreeItem();
        $item->date_latest = date('Y');
        $authority = factory(Authority::class)->make(['death_year' => 2000]);
        $item->authorities->add($authority);

        $this->assertEquals((new \DateTime('2071-01-01'))->getTimestamp(), $item->freeFrom());
    }

    public function testFreeFromAuthorityDeathYearNull() {
        $item = $this->createFreeItem();
        $item->date_latest = date('Y');
        $authority = factory(Authority::class)->make(['death_year' => null]);
        $item->authorities->add($authority);

        $this->assertTrue($item->isFree());
    }

    public function testIsFree() {
        $item = $this->createFreeItem();
        $this->assertTrue($item->isFree());
    }

    public function testIsFreeGallery() {
        $item = $this->createFreeItem();
        $item->gallery = '';
        $this->assertFalse($item->isFree());
    }

    public function testIsFreeAuthorDeathYearNow() {
        $item = $this->createFreeItem();
        $item->date_latest = date('Y');
        $authority = factory(Authority::class)->make(['death_year' => date('Y')]);
        $item->authorities->add($authority);
        $this->assertFalse($item->isFree());
    }

    public function testIsFreeDateLatestNow() {
        $item = $this->createFreeItem();
        $item->date_latest = date('Y');
        $this->assertTrue($item->isFree());
    }

    public function testGetIndexedDataFallbackLocale()
    {
        /** @var Item $item */
        $item = factory(Item::class)->make([
            'title' => 'Názov',
            'title:en' => 'Title',
            'description' => 'Popis',
        ]);

        $data = $item->getIndexedData('en');
        $this->assertEquals($data['title'], 'Title');
        $this->assertEquals($data['description'], 'Popis');
    }

    public function testMakeArrayEmpty()
    {
        /** @var Item $item */
        $item = factory(Item::class)->make();
        $array = $item->makeArray('');
        $this->assertEquals([], $array);
    }

    public function testMakeArrayTrimmed()
    {
        /** @var Item $item */
        $item = factory(Item::class)->make();
        $array = $item->makeArray(' first ; second ');
        $this->assertEquals(['first', 'second'], $array);
    }

    public function testWorkTypes()
    {
        /** @var Item $item */
        $item = factory(Item::class)->make([
            'work_type' => 'kresba, prípravná, návrh; iné médiá, album'
        ]);
        $workTypes = $item->work_types;
        $this->assertEquals(
            [
                [
                    [
                        'name' => 'kresba',
                        'path' => 'kresba',
                    ],
                    [
                        'name' => 'prípravná',
                        'path' => 'kresba/prípravná',
                    ],
                    [
                        'name' => 'návrh',
                        'path' => 'kresba/prípravná/návrh',
                    ]
                ],
                [
                    [
                        'name' => 'iné médiá',
                        'path' => 'iné médiá',
                    ],
                    [
                        'name' => 'album',
                        'path' => 'iné médiá/album',
                    ]
                ]
            ],
            $workTypes
        );
    }

    protected function createFreeItem() {
        return factory(Item::class)->make([
            'gallery' => 'Slovenská národná galéria, SNG',
            'author' => 'neznámy',
            'date_latest' => 1 // CE
        ]);
    }
}
