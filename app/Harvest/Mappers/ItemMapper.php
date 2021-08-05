<?php

namespace App\Harvest\Mappers;

use App\Item;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ItemMapper extends AbstractMapper
{
    protected $modelClass = Item::class;

    public function mapId(array $row) {
        if (isset($row['id'][0])) {
            return $row['id'][0];
        }
    }

    public function mapIdentifier(array $row) {
        return Arr::first($row['identifier'], function ($identifier) use ($row) {
            return $identifier != $row['id'][0] && !Str::startsWith($identifier, 'http');
        });
    }

    public function mapTitle(array $row, $locale) {
        if ($locale == 'sk') {
            return $row['title'] ?: null;
        }

        return $this->getLocalized($row, 'title_translated', $locale) ?: null;
    }

    public function mapWorkType(array $row, $locale) {
        $work_type = $this->getLocalized($row, 'work_type', $locale);
        return $this->serialize($work_type, ', ') ?: null;
    }

    public function mapObjectType(array $row, $locale) {
        return $this->getLocalized($row, 'object_type', $locale) ?: null;
    }

    public function mapTechnique(array $row, $locale) {
        return $this->getLocalized($row, 'format', $locale) ?: null;
    }

    public function mapMedium(array $row, $locale) {
        return $this->getLocalized($row, 'format_medium', $locale) ?: null;
    }

    public function mapSubject(array $row, $locale) {
        $subjects = $this->getLocalized($row, 'subject', $locale);
        $subjects = array_filter($subjects, function ($subject) {
            return starts_with_upper((string)$subject);
        });
        $subjects = array_map(function ($subject) {
            return Str::lower($subject);
        }, $subjects);

        return $subjects ?: null;
    }

    public function mapTopic(array $row, $locale) {
        $topics = $this->getLocalized($row, 'subject', $locale);
        $topics = array_filter($topics, function ($topic) {
            return !starts_with_upper((string)$topic);
        });

        return $topics ?: null;
    }

    public function mapMeasurement(array $row, $locale) {
        if ($locale == 'sk') {
            return array_map('trim', $row['extent']) ?: null;
        }
    }

    public function mapInscription(array $row, $locale) {
        if ($locale == 'sk') {
            return $row['description'] ?: null;
        }
    }

    public function mapPlace(array $row, $locale) {
        if ($locale == 'sk') {
            return $row['subject_place'] ?: null;
        }
    }

    public function mapGallery(array $row, $locale) {
        if ($locale == 'sk') {
            return $row['gallery'] ?: null;
        }
    }

    public function mapCredit(array $row, $locale) {
        return $this->getLocalized($row, 'credit', $locale) ?: null;
    }

    public function mapAuthor(array $row) {
        return array_filter($row['creator'], function ($creator) {
            return !str_contains($creator, 'urn:');
        });
    }

    public function mapDateEarliest(array $row) {
        if (isset($row['created'][0])) {
            $dating = explode('/', $row['created'][0]);
            return !empty($dating[0]) ? $dating[0] : null;
        }
    }

    public function mapDateLatest(array $row) {
        if (isset($row['created'][0])) {
            $dating = explode('/', $row['created'][0]);
            return !empty($dating[1]) ? $dating[1] : $this->mapDateEarliest($row);
        }
    }

    public function mapDating(array $row, $locale) {
        if ($locale != 'sk') {
            return;
        }

        $datings = array_filter($row['created'], function($dating) {
            return str_contains($dating, ', ');
        });

        $datings = array_map(function($dating) {
            $dating = explode(', ', $dating);
            return end($dating);
        }, $datings);

        if ($datings) {
            return implode(', ', $datings);
        } elseif (isset($row['created'][0])) {
            return $row['created'][0];
        }
    }

    public function mapRelationshipType(array $row, $locale) {
        if ($locale != 'sk') {
            return null;
        }

        $parts = $this->parseRelatedParts($row);
        return isset($parts['type']) && $parts['type'] !== '' ? $parts['type'] : null;
    }

    public function mapRelatedWork(array $row, $locale) {
        if ($locale != 'sk') {
            return null;
        }

        $parts = $this->parseRelatedParts($row);
        return isset($parts['name']) && $parts['name'] !== '' ? $parts['name'] : null;
    }

    public function mapRelatedWorkOrder(array $row) {
        $parts = $this->parseRelatedParts($row);
        return isset($parts['order']) && $parts['order'] !== '' ? $parts['order'] : null;
    }

    public function mapRelatedWorkTotal(array $row) {
        $parts = $this->parseRelatedParts($row);
        return isset($parts['total']) && $parts['total'] !== '' ? $parts['total'] : null;
    }

    public function mapImgUrl(array $row) {
        return Arr::first($row['identifier'], function ($identifier) {
            return str_contains($identifier, 'getimage');
        });
    }

    public function mapWorkLevel() {}

    protected function parseRelatedParts($row)
    {
        return preg_match(
            '#^(?<type>.*?)(:(?<name>.*?)\s*(\((?<order>\d*)/(?<total>\d*)\))?)?$#',
            $row['relation_isPartOf'][0] ?? null,
            $match
        ) ? $match : null;
    }
}
