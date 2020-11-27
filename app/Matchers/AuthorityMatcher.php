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
     * @param bool $onlyExisting
     * @return Collection
     */
    public function matchAll(Item $item, $onlyExisting = false)
    {
        return collect($item->authors)
            ->keys()
            ->mapWithKeys(function ($author) use ($item, $onlyExisting) {
                return [$author => $this->match($author, $item, $onlyExisting)];
            });
    }

    /**
     * @param string $author
     * @param Item $item
     * @param bool $onlyExisting
     * @return Authority[]|Collection
     */
    public function match($author, Item $item, $onlyExisting = false)
    {
        $parsed = $this->authorParser->parse($author);
        if ($parsed['surname'] && $parsed['name']) {
            $fullname = sprintf('%s, %s', $parsed['surname'], $parsed['name']);
        } else {
            $fullname = $parsed['name'];
        }

        $existingMatches = $item->authorities
            ->filter(function (Authority $authority) use ($fullname) {
                return $authority->names
                    ->pluck('name')
                    ->add($authority->name)
                    ->contains($fullname);
            });

        if ($existingMatches->isNotEmpty() || $onlyExisting) {
            return $existingMatches;
        }

        return $this->findByFullname($fullname)
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

                return $dateEarliest <= $deathYear && $dateLatest >= $birthYear;
            });
    }

    /**
     * @param string $fullname
     * @return Authority[]|Collection
     */
    protected function findByFullname($fullname)
    {
        $authorities = Authority::where('name', $fullname)->get();
        return AuthorityName::where('name', $fullname)->get()
            ->map(function (AuthorityName $authorityName) {
                return $authorityName->authority;
            })
            ->merge($authorities);
    }
}