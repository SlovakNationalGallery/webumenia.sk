<?php

namespace Tests\Feature\Admin;

use App\Authority;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorityTest extends TestCase
{
    use RefreshDatabase;

    public function testLinkLabelsAreGeneratedFromUrlAsFallback()
    {
        $authority = factory(Authority::class)->create();
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->put(
            route('authority.update', $authority),
            array_merge($authority->getAttributes(), [
                'externalLinks' => [
                    [
                        'id' => '',
                        'url' => 'https://www.register-architektury.sk/architekti/titl-lubomir',
                        'label' => '',
                    ],
                ],
            ])
        );

        $this->assertEquals(
            'www.register-architektury.sk',
            $authority->externalLinks()->first()['label']
        );
    }
}
