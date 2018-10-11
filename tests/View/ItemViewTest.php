<?php

namespace Tests\View;

use App\Item;
use App\Role;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ItemViewTest extends TestCase
{
    use DatabaseMigrations;

    public function testAddImage() {
        $role = factory(Role::class)->create(['name' => 'admin']);
        $user = factory(User::class)->create();
        $user->roles()->attach($role);
        $item = factory(Item::class)->create();

        $form = $this->actingAs($user)
            ->visit(sprintf('/item/%s/edit', $item->id))
            ->getForm('Uložiť');
        $url = $this->faker->url;
        $values = $form->getPhpValues();
        $values['item']['images'][0]['iipimg_url'] = $url;
        $this->makeRequest($form->getMethod(), $form->getUri(), $values);

        $this->assertEquals($url, $item->fresh()->images[0]->iipimg_url);
    }
}