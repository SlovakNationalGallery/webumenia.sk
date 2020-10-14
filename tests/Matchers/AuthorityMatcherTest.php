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

    public function testMatchAll()
    {
        $authority = factory(Authority::class)->create([
            'name' => 'Wouwerman, Philips',
            'birth_year' => null,
            'death_year' => null,
        ]);
        $item = factory(Item::class)->create([
            'author' => 'Wouwerman, Philips'
        ]);

        $matcher = new AuthorityMatcher(new AuthorParser());
        $matched = $matcher->matchAll($item);
        $authorities = $matched['Wouwerman, Philips'];

        $this->assertCount(1, $authorities);
        $this->assertTrue($authority->is($authorities[0]));
    }

    public function testMatch()
    {
        factory(Authority::class)->create([
            'name' => 'Wouwerman, Philips',
            'birth_year' => null,
            'death_year' => null,
        ]);
        factory(Authority::class)->create([
            'name' => 'Caullery - následovník, Louis',
            'birth_year' => null,
            'death_year' => null,
        ]);
        $item = factory(Item::class)->create([
            'author' => 'Wouwerman, Philips'
        ]);

        $matcher = new AuthorityMatcher(new AuthorParser());
        $authorities = $matcher->match('Wouwerman, Philips', $item);

        $this->assertCount(1, $authorities);
    }

    public function testMatch_DuplicateExists()
    {
        factory(Authority::class)->create([
            'name' => 'Wouwerman, Philips',
            'birth_year' => null,
            'death_year' => null,
        ]);
        factory(Authority::class)->create([
            'name' => 'Wouwerman, Philips',
            'birth_year' => null,
            'death_year' => null,
        ]);
        $item = factory(Item::class)->create([
            'author' => 'Wouwerman, Philips'
        ]);

        $matcher = new AuthorityMatcher(new AuthorParser());
        $authorities = $matcher->match('Wouwerman, Philips', $item);

        $this->assertCount(2, $authorities);
    }

    public function testMatch_DuplicateAndRelationAlreadyExists()
    {
        $related = factory(Authority::class)->create([
            'name' => 'Wouwerman, Philips',
            'birth_year' => null,
            'death_year' => null,
        ]);
        factory(Authority::class)->create([
            'name' => 'Wouwerman, Philips',
            'birth_year' => null,
            'death_year' => null,
        ]);
        $item = factory(Item::class)->create([
            'author' => 'Wouwerman, Philips'
        ]);
        $item->authorities()->attach($related);

        $matcher = new AuthorityMatcher(new AuthorParser());
        $authorities = $matcher->match('Wouwerman, Philips', $item);

        $this->assertCount(1, $authorities);
    }
}