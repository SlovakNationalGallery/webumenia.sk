<?php

namespace Tests\Models;

use App\Authority;
use App\Item;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use DatabaseMigrations;

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

        $this->assertEquals(Item::FREE_NEVER, $item->freeFrom());
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

    public function testIsFreeAuthorDeathYearNull() {
        $item = $this->createFreeItem();
        $item->date_latest = date('Y');
        $authority = factory(Authority::class)->make(['death_year' => null]);
        $item->authorities->add($authority);
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

    protected function createFreeItem() {
        return factory(Item::class)->make([
            'gallery' => 'Slovenská národná galéria, SNG',
            'author' => 'neznámy',
            'date_latest' => 1 // CE
        ]);
    }
}
