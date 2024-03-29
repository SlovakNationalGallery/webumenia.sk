<?php

namespace App\Harvest\Mappers;

use Illuminate\Support\Str;

class AuthorityMapper extends BaseAuthorityMapper
{
    public function mapType(array $row)
    {
        if (!isset($row['type'])) {
            return null;
        }
        return array_map(function ($type) {
            return Str::lower($type);
        }, $row['type']);
    }

    public function mapTypeOrganization(array $row)
    {
        if (!isset($row['type_organization'])) {
            return null;
        }
        return $row['type_organization'];
    }

    public function mapName(array $row)
    {
        if (!isset($row['name'])) {
            return null;
        }
        return $row['name'];
    }

    public function mapSex(array $row)
    {
        if (!isset($row['sex'])) {
            return null;
        }
        return array_map(function ($sex) {
            return Str::lower($sex);
        }, $row['sex']);
    }

    public function mapBirthPlace(array $row, $locale)
    {
        if (isset($row['birth_place'][0])) {
            return $this->chooseTranslation($row['birth_place'][0], $locale);
        }
    }

    public function mapDeathPlace(array $row, $locale)
    {
        if (isset($row['death_place'][0])) {
            return $this->chooseTranslation($row['death_place'][0], $locale);
        }
    }

    public function mapBirthDate(array $row)
    {
        if (!isset($row['birth_date'])) {
            return null;
        }
        return $row['birth_date'];
    }

    public function mapDeathDate(array $row)
    {
        if (!isset($row['death_date'])) {
            return null;
        }
        return $row['death_date'];
    }

    public function mapBirthYear(array $row)
    {
        if (isset($row['birth_date'][0])) {
            return $this->parseYear($row['birth_date'][0]);
        }
    }

    public function mapDeathYear(array $row)
    {
        if (isset($row['death_date'][0])) {
            return $this->parseYear($row['death_date'][0]);
        }
    }

    public function mapRoles(array $row)
    {
        if (!isset($row['roles'])) {
            return null;
        }
        return $row['roles'];
    }

    /**
     * @param string $date
     * @return int|null
     */
    public function parseYear($date)
    {
        $exploded = explode('.', $date);
        $end = end($exploded);
        if ($end === '') {
            return null;
        }
        return (int) $end;
    }
}
