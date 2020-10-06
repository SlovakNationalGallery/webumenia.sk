<?php

namespace App\Matchers;

use App\Authority;
use App\AuthorityName;
use App\Item;
use App\Parsers\AuthorParser;
use Illuminate\Support\Collection;

class AuthorityMatcher
{
    const MAX_LIFE_SPAN = 120;

    protected $authorParser;

    public function __construct(AuthorParser $authorParser) {
        $this->authorParser = $authorParser;
    }

    /**
     * @param Item $item
     * @return Authority[]|Collection
     * @throws \Exception
     */
    public function matchAll(Item $item)
    {
        return collect($item->makeArray($item->author))
            ->map(function ($author) use ($item) {
                return $this->match($author, $item);
            })
            ->filter();
    }

    /**
     * @param string $author
     * @param Item $item
     * @return Authority|null
     * @throws \Exception
     */
    public function match($author, Item $item)
    {
        $parsed = $this->authorParser->parse($author);
        $fullname = sprintf('%s, %s', $parsed['surname'], $parsed['name']);
        $authorities = $this->findAuthorities($fullname, $item);

        if (count($authorities) > 1) {
            $ids = $authorities->pluck('id')->implode(', ');
            throw new \Exception('Multiple authorities matched (%s)', $ids);
        }

        return $authorities[0] ?? null;
    }

    /**
     * @param string $author
     * @param Item $item
     * @return Authority[]|Collection
     */
    protected function findAuthorities($author, Item $item)
    {
        $authorities = Authority::where('name', $author)->get();
        return AuthorityName::where('name', $author)->get()
            ->map(function (AuthorityName $name) {
                return $name->authority;
            })
            ->merge($authorities)
            ->filter(function (Authority $authority) use ($item) {
                $birthYear = $authority->birth_year;
                $deathYear = $authority->death_year;

                if ($birthYear === null && $deathYear === null) {
                    return true;
                } elseif ($birthYear === null) {
                    $birthYear = $authority->death_year - Item::GUESSED_AUTHORISM_TIMESPAN;
                } elseif ($deathYear === null) {
                    $deathYear = $birthYear + self::MAX_LIFE_SPAN;
                }

                return $birthYear < $item->date_earliest && $deathYear >= $item->date_latest;
            });
    }
}