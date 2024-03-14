<?php

namespace Tests\Models;

use App\Authority;
use Tests\TestCase;

class AuthorityTest extends TestCase
{
    public function testRoles()
    {
        $authority = Authority::factory()->make([
            'roles' => ['maliar/painter', 'untranslated-role'],
            'sex' => 'female',
        ]);

        $this->assertEquals(['maliar'], $authority->getIndexedData('sk')['role']->toArray());
        $this->assertEquals([], $authority->getIndexedData('cs')['role']->toArray());
        $this->assertEquals(['painter'], $authority->getIndexedData('en')['role']->toArray());

        $this->assertEquals(
            [(object) ['indexed' => 'maliar', 'formatted' => 'maliarka']],
            $authority->translatedRoles->toArray()
        );
    }
}
