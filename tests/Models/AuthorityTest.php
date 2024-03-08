<?php

namespace Tests\Models;

use App\Authority;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorityTest extends TestCase
{
    use RefreshDatabase;

    public function testRoles()
    {
        $authority = Authority::factory()->create([
            'roles' => ['maliar/painter', 'untranslated-role'],
            'sex' => 'female',
        ]);

        $this->assertEquals(['maliar'], $authority->getIndexedData('sk')['role']->toArray());
        $this->assertEquals(['maliar'], $authority->getIndexedData('cs')['role']->toArray());
        $this->assertEquals(['painter'], $authority->getIndexedData('en')['role']->toArray());

        $this->assertEquals(
            [(object) ['indexed' => 'maliar', 'formatted' => 'maliarka']],
            $authority->translatedRoles->toArray()
        );
    }
}
