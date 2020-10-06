<?php

namespace Tests\Parsers;

use App\Parsers\AuthorParser;
use Tests\TestCase;

class AuthorParserTest extends TestCase
{
    public function testParseSimple()
    {
        $author = 'Wouwerman, Philips';
        $parser = new AuthorParser();
        $parsed = $parser->parse($author);

        $this->assertEquals('Philips', $parsed['name']);
        $this->assertEquals('Wouwerman', $parsed['surname']);
    }

    public function testParseWithRole()
    {
        $author = 'Caullery - následovník, Louis';
        $parser = new AuthorParser();
        $parsed = $parser->parse($author);

        $this->assertEquals('Louis', $parsed['name']);
        $this->assertEquals('Caullery', $parsed['surname']);
        $this->assertEquals('následovník', $parsed['role']);
    }

    public function testParseWithAlternativeName()
    {
        $author = 'Friedberg-Mirohorský, Salomon (Emanuel)';
        $parser = new AuthorParser();
        $parsed = $parser->parse($author);

        $this->assertEquals('Salomon', $parsed['name']);
        $this->assertEquals('Friedberg-Mirohorský', $parsed['surname']);
        $this->assertEquals('Emanuel', $parsed['alt_name']);
    }

    public function testParseWithAlternativeSurname()
    {
        $author = 'Hlava (Hlava-Bém), Vratislav';
        $parser = new AuthorParser();
        $parsed = $parser->parse($author);

        $this->assertEquals('Vratislav', $parsed['name']);
        $this->assertEquals('Hlava', $parsed['surname']);
        $this->assertEquals('Hlava-Bém', $parsed['alt_surname']);
    }
}