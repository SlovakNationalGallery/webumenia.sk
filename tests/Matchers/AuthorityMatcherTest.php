<?php

namespace Tests\Matchers;

use App\Authority;
use App\Item;
use App\Matchers\AuthorityMatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorityMatcherTest extends TestCase
{
    use RefreshDatabase;

    public function testMatchAll()
    {
        $authority = Authority::factory()->create([
            'name' => 'Wouwerman, Philips',
            'birth_year' => null,
            'death_year' => null,
        ]);
        $item = Item::factory()->create([
            'author' => 'Wouwerman, Philips',
        ]);

        $matcher = new AuthorityMatcher();
        $matched = $matcher->matchAll($item);
        $authorities = $matched['Wouwerman, Philips'];

        $this->assertCount(1, $authorities);
        $this->assertTrue($authority->is($authorities[0]));
    }

    public function testMatch()
    {
        Authority::factory()->create([
            'name' => 'Wouwerman, Philips',
            'birth_year' => null,
            'death_year' => null,
        ]);
        Authority::factory()->create([
            'name' => 'Caullery - následovník, Louis',
            'birth_year' => null,
            'death_year' => null,
        ]);
        $item = Item::factory()->create([
            'author' => 'Wouwerman, Philips',
        ]);

        $matcher = new AuthorityMatcher();
        $authorities = $matcher->match('Wouwerman, Philips', $item);

        $this->assertCount(1, $authorities);
    }

    public function testMatch_DuplicateExists()
    {
        Authority::factory()->create([
            'name' => 'Wouwerman, Philips',
            'birth_year' => null,
            'death_year' => null,
        ]);
        Authority::factory()->create([
            'name' => 'Wouwerman, Philips',
            'birth_year' => null,
            'death_year' => null,
        ]);
        $item = Item::factory()->create([
            'author' => 'Wouwerman, Philips',
        ]);

        $matcher = new AuthorityMatcher();
        $authorities = $matcher->match('Wouwerman, Philips', $item);

        $this->assertCount(2, $authorities);
    }

    public function testMatch_DuplicateAndRelationAlreadyExists()
    {
        $related = Authority::factory()->create([
            'name' => 'Wouwerman, Philips',
            'birth_year' => null,
            'death_year' => null,
        ]);
        Authority::factory()->create([
            'name' => 'Wouwerman, Philips',
            'birth_year' => null,
            'death_year' => null,
        ]);
        $item = Item::factory()->create([
            'author' => 'Wouwerman, Philips',
        ]);
        $item->authorities()->attach($related);

        $matcher = new AuthorityMatcher();
        $authorities = $matcher->match('Wouwerman, Philips', $item);

        $this->assertCount(1, $authorities);
    }

    public function testMatch_Name()
    {
        $authority = Authority::factory()->create([
            'name' => 'Rembrandt van Rijn',
            'birth_year' => null,
            'death_year' => null,
        ]);
        $item = Item::factory()->create([
            'author' => 'Rembrandt van Rijn',
        ]);

        $matcher = new AuthorityMatcher();
        $authorities = $matcher->match('Rembrandt van Rijn', $item);

        $this->assertCount(1, $authorities);
        $this->assertTrue($authority->is($authorities[0]));
    }

    public function testMatch_SwappedName()
    {
        $authority = Authority::factory()->create([
            'name' => 'Wouwerman, Philips',
            'birth_year' => null,
            'death_year' => null,
        ]);
        $item = Item::factory()->create([
            'author' => 'Philips Wouwerman',
        ]);

        $matcher = new AuthorityMatcher();
        $authorities = $matcher->match('Philips Wouwerman', $item);

        $this->assertCount(1, $authorities);
        $this->assertTrue($authority->is($authorities[0]));
    }

    public function testParseName()
    {
        $author = 'Rembrandt van Rijn';
        $parsed = AuthorityMatcher::parse($author);

        $this->assertEquals('Rembrandt van Rijn', $parsed['name']);
    }

    public function testParseNameWithAltNameInBrackets()
    {
        $author = 'Toyen (Marie Čermínová)';
        $parsed = AuthorityMatcher::parse($author);

        $this->assertEquals('Toyen', $parsed['name']);
        $this->assertEquals('Marie Čermínová', $parsed['alt_name']);
    }

    public function testParseNameWithAltNameAfterSlash()
    {
        $author = 'Toyen /Marie Čermínová';
        $parsed = AuthorityMatcher::parse($author);

        $this->assertEquals('Toyen', $parsed['name']);
        $this->assertEquals('Marie Čermínová', $parsed['alt_name']);
    }

    public function testParseSurnameAndName()
    {
        $author = 'Wouwerman, Philips';
        $parsed = AuthorityMatcher::parse($author);

        $this->assertEquals('Philips', $parsed['name']);
        $this->assertEquals('Wouwerman', $parsed['surname']);
    }

    public function testParseSurnameAndNameWithRole()
    {
        $author = 'Caullery - následovník, Louis';
        $parsed = AuthorityMatcher::parse($author);

        $this->assertEquals('Louis', $parsed['name']);
        $this->assertEquals('Caullery', $parsed['surname']);
        $this->assertEquals('následovník', $parsed['role']);
    }

    public function testParseSurnameAndNameWithAltName()
    {
        $author = 'Friedberg-Mirohorský, Salomon (Emanuel)';
        $parsed = AuthorityMatcher::parse($author);

        $this->assertEquals('Salomon', $parsed['name']);
        $this->assertEquals('Friedberg-Mirohorský', $parsed['surname']);
        $this->assertEquals('Emanuel', $parsed['alt_name']);
    }

    public function testParseSurnameWithAltSurnameAndName()
    {
        $author = 'Hlava (Hlava-Bém), Vratislav';
        $parsed = AuthorityMatcher::parse($author);

        $this->assertEquals('Vratislav', $parsed['name']);
        $this->assertEquals('Hlava', $parsed['surname']);
        $this->assertEquals('Hlava-Bém', $parsed['alt_surname']);
    }
}
