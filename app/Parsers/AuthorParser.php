<?php

namespace App\Parsers;

class AuthorParser
{
    public function parse($author)
    {
        if (!preg_match('/^(?<surname>.*?)\s*(?<alt_surname>\(.*\))?(?<role>\s+-\s+.*)?,\s+(?<name>.*?)\s*(?<alt_name>\(.*\))?$/', $author, $matches)) {
            return null;
        }

        return [
            'surname' => $matches['surname'],
            'alt_surname' => trim($matches['alt_surname'], '()'),
            'role' => ltrim($matches['role'], ' -'),
            'name' => $matches['name'],
            'alt_name' => trim($matches['alt_name'] ?? '', '()'),
        ];
    }
}