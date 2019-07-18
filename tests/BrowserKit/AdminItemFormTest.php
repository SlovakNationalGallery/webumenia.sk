<?php

namespace Tests\BrowserKit;

use App\Item;
use App\Role;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutEvents;

class AdminItemFormTest extends BrowserKitTestCase
{
    use DatabaseMigrations, WithoutEvents;

    /** @var User */
    protected $user;

    public function setUp() {
        parent::setUp();
        $role = factory(Role::class)->create(['name' => 'admin']);
        $this->user = factory(User::class)->create();
        $this->user->roles()->attach($role);
        $this->actingAs($this->user);
    }

    public function testCreate() {
        $filename = sprintf(
            "%s%s%s",
            __DIR__,
            DIRECTORY_SEPARATOR,
            'test.jpeg'
        );

        $this->visit('/item/create')
            ->type($this->faker->name, 'item[author]')
            ->attach($filename, 'item[primary_image]')
            ->press('Uložiť')
            ->seePageIs('/item');
    }

    public function testEdit() {
        $item = factory(Item::class)->create();

        $this->visit(sprintf('/item/%s/edit', $item->id))
            ->press('Uložiť')
            ->seePageIs('/item');
    }

    public function testAddImage() {
        $item = factory(Item::class)->create();

        $form = $this->visit(sprintf('/item/%s/edit', $item->id))
            ->getForm('Uložiť');
        $url = $this->faker->url;
        $values = $form->getPhpValues();
        $values['item']['images'][0]['iipimg_url'] = $url;
        $this->makeRequest($form->getMethod(), $form->getUri(), $values);

        $this->assertEquals($url, $item->fresh()->images[0]->iipimg_url);
    }
}