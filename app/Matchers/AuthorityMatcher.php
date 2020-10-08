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
     * @return Collection
     */
    public function matchAll(Item $item)
    {
        return collect(array_keys($item->authors))
            ->mapWithKeys(function ($author) use ($item) {
                return [$author => $this->match($author, $item)];
            });
    }

    /**
     * @param string $author
     * @param Item $item
     * @return Authority[]|Collection
     */
    public function match($author, Item $item)
    {
        $parsed = $this->authorParser->parse($author);
        $fullname = sprintf('%s, %s', $parsed['surname'], $parsed['name']);
        return $this->findByFullnameAndDates($fullname, $item);
    }

    /**
     * @param string $fullname
     * @param Item $item
     * @return Authority[]|Collection
     */
    protected function findByFullnameAndDates($fullname, Item $item)
    {
        $authorities = Authority::where('name', $fullname)->get();
        return AuthorityName::where('name', $fullname)->get()
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

                $dateEarliest = $item->date_earliest;
                $dateLatest = $item->date_latest;
                if ($dateEarliest === null && $dateLatest === null) {
                    return true;
                } elseif ($dateEarliest === null) {
                    $dateEarliest = $dateLatest;
                } elseif ($dateLatest === null) {
                    $dateLatest = $dateEarliest;
                }

                return $birthYear < $dateEarliest && $deathYear >= $dateLatest;
            });
    }
}