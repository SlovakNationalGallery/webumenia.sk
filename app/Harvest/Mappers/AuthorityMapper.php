<?php

namespace App\Harvest\Mappers;

use App\Authority;
use Illuminate\Support\Str;

class AuthorityMapper extends AbstractModelMapper
{
    protected $modelClass = Authority::class;

    public function mapId(array $row) {
        if (isset($row['id'][0])) {
            return (int)$this->parseId($row['id'][0]);
        }
    }

    public function mapType(array $row) {
        return 'author';
        // return array_map(function ($type) {
        //     return Str::lower($type);
        // }, $row['type']);
    }

    public function mapTypeOrganization(array $row) {
        return $row['type_organization'];
    }

    public function mapName(array $row) {
        return $row['name'];
    }

    public function mapSex(array $row) {
        return array_map(function ($sex) {
            return Str::lower($sex);
        }, $row['sex']);
    }

    public function mapBiography(array $row) {
        if (!isset($row['biography'][0])) {
            return '';
        }

        $biography = str_after($row['biography'][0], '(ZNÁMY)');
        if (str_contains($biography, 'http')) {
            return '';
        }

        return $biography;
    }

    public function mapBirthPlace(array $row, $locale) {
        if (isset($row['birth_place'][0])) {
            return $this->chooseTranslation($row['birth_place'][0], $locale);
        }
    }

    public function mapDeathPlace(array $row, $locale) {
        if (isset($row['death_place'][0])) {
            return $this->chooseTranslation($row['death_place'][0], $locale);
        }
    }

    public function mapBirthDate(array $row) {
        return $row['birth_date'];
    }

    public function mapDeathDate(array $row) {
        return $row['death_date'];
    }

    public function mapBirthYear(array $row) {
        if (isset($row['birth_date'][0])) {
            return $this->parseYear($row['birth_date'][0]);
        }
    }

    public function mapDeathYear(array $row) {
        if (isset($row['death_date'][0])) {
            return $this->parseYear($row['death_date'][0]);
        }
    }

    public function mapRoles(array $row, $locale) {
        $roles = [];
        foreach ($row['roles'] as $role) {
            $roles[] = $this->chooseTranslation($role, $locale);
        }

        return $roles ?: null;
    }

    /**
     * @param string $date
     * @return int
     */
    public function parseYear($date) {
        $exploded = explode('.', $date);
        return (int)end($exploded);
    }
}