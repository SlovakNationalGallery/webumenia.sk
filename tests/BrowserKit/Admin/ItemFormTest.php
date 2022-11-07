<?php

namespace Tests\BrowserKit\Admin;

use App\Item;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BrowserKitTestCase;

class ItemFormTest extends BrowserKitTestCase
{
    use RefreshDatabase;

    /** @var User */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->user);
    }

    public function testCreate()
    {
        $filename = sprintf('%s%s%s', __DIR__, DIRECTORY_SEPARATOR, 'test.jpeg');

        $this->visit('/item/create')
            ->attach($filename, 'item[primary_image]')
            ->type('nobody', 'item[author]')
            ->press('Uložiť')
            ->seePageIs('/item');
    }

    public function testEdit()
    {
        $item = Item::factory()->create();

        $this->visit(sprintf('/item/%s/edit', $item->id))
            ->press('Uložiť')
            ->seePageIs('/item');
    }

    public function testAddImage()
    {
        $item = Item::factory()->create();

        $form = $this->visit(sprintf('/item/%s/edit', $item->id))->getForm('Uložiť');
        $url = 'http://some-url.coml';
        $values = $form->getPhpValues();
        $values['item']['images'][0]['iipimg_url'] = $url;
        $this->makeRequest($form->getMethod(), $form->getUri(), $values);

        $this->assertEquals($url, $item->fresh()->images[0]->iipimg_url);
    }
}
