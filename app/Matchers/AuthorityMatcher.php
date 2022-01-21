<?php

namespace App\Matchers;

use App\Authority;
use App\AuthorityName;
use App\Item;
use Illuminate\Support\Collection;

class AuthorityMatcher
{
    const MAX_LIFE_SPAN = 120;

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
        $parsed = $this::parse($author);
        if ($parsed['surname'] && $parsed['name']) {
            $fullname = sprintf('%s %s', $parsed['name'], $parsed['surname']);
        } else {
            $fullname = $parsed['name'];
        }

        $existingMatches = $item->authorities
            ->filter(function (Authority $authority) use ($fullname) {
                return $authority->names
                    ->pluck('name')
                    ->map('formatName')
                    ->add($authority->formated_name)
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
        $authorities = Authority::whereFirstNameLastName($fullname)
            ->get();
        return AuthorityName::whereFirstNameLastName($fullname)
            ->get()
            ->map(function (AuthorityName $authorityName) {
                return $authorityName->authority;
            })
            ->merge($authorities);
    }

    public static function parse($author)
    {
        if (!preg_match('/^((?<surname>.*?)\s*(?<alt_surname>\(.*\))?(?<role>\s+-\s+.*)?,\s+)?(?<name>.*?)\s*(?<alt_name>\(.*\))?$/', $author, $matches)) {
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