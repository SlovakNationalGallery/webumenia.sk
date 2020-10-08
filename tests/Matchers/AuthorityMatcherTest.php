<?php

namespace Tests\Matchers;

use App\Authority;
use App\Item;
use App\Matchers\AuthorityMatcher;
use App\Parsers\AuthorParser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AuthorityMatcherTest extends TestCase
{
    use DatabaseMigrations;

    public function testMatch()
    {
        $authority = factory(Authority::class)->create([
            'name' => 'Wouwerman, Philips',
            'birth_year' => null,
            'death_year' => null,
        ]);
        $item = new Item();
        $item->author = 'Wouwerman, Philips';

        $matcher = new AuthorityMatcher(new AuthorParser());
        $matched = $matcher->matchAll($item);
        $authorities = $matched['Wouwerman, Philips'];

        $this->assertCount(1, $authorities);
        $this->assertTrue($authority->is($authorities[0]));
    }
}