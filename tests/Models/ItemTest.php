<?php


namespace Tests\Models;


use App\Item;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use DatabaseMigrations;

    public function testTranslationFallback() {
        $item = factory(Item::class)->create([
            'title:sk' => 'a',
            'title:cs' => 'b',
        ]);

        \App::setLocale('cs');
        $this->assertEquals('b', $item->title);

        \App::setLocale('en');
        $this->assertEquals('a', $item->title);
    }
}