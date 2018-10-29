<?php

namespace App\Harvest\Mappers;

use App\Item;
use Illuminate\Support\Str;

class ItemMapper extends AbstractModelMapper
{
    protected $modelClass = Item::class;

    public function mapId(array $row) {
        if (isset($row['id'][0])) {
            return $row['id'][0];
        }
    }

    public function mapIdentifier(array $row) {
        return array_first($row['identifier'], function ($i, $identifier) use ($row) {
            return $identifier != $row['id'][0] && starts_with_upper($identifier);
        });
    }

    public function mapTitle(array $row, $locale) {
        if ($locale == 'sk') {
            return $row['title'] ?: null;
        }

        return $this->getLocalized($row, 'title_translated', $locale) ?: null;
    }

    public function mapWorkType(array $row, $locale) {
        $work_type = $this->getLocalized($row, 'type', $locale);
        return $this->serialize($work_type, ', ') ?: null;
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
            return $row['provenance'] ?: null;
        }
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

        $dating_text = null;
        if (!empty($row['created'][1])) {
            $dating_text_array = explode(', ', $row['created'][1]);
            $dating_text = end($dating_text_array);
        } elseif (isset($row['created'][0])) {
            $dating_text = $row['created'][0];
        }

        return $dating_text;
    }

    public function mapRelationshipType(array $row, $locale) {
        if ($locale != 'sk') {
            return;
        }

        $related_parts = $this->getRelatedParts($row);
        if (isset($related_parts[0])) {
            return $related_parts[0];
        }
    }

    public function mapRelatedWork(array $row, $locale) {
        if ($locale != 'sk') {
            return;
        }

        $related_work_order = $this->getRelatedWorkOrderPart($row);
        if (!is_numeric($related_work_order)) {
            return $related_work_order;
        }

        $related_parts = $this->getRelatedParts($row);
        if (count($related_parts) < 2) {
            return;
        }

        return trim(preg_replace('/\s*\([^)]*\)/', '', $related_parts[1]));
    }

    public function mapRelatedWorkOrder(array $row) {
        $related_work_order = $this->getRelatedWorkOrderPart($row, $total = false);
        if (!is_numeric($related_work_order)) {
            return 0;
        }

        return (int)$related_work_order;
    }

    public function mapRelatedWorkTotal(array $row) {
        $related_work_total = $this->getRelatedWorkOrderPart($row, $total = true);
        if (!is_numeric($related_work_total)) {
            return 0;
        }

        return (int)$related_work_total;
    }

    public function mapDescription() {}

    public function mapWorkLevel() {}

    public function mapItemType() {
        return '';
    }

    public function mapFeatured() {
        return false;
    }

    public function mapContributor(array $row) {
        return $row['contributor'] ?: null;
    }

    protected function getRelatedParts(array $row) {
        if (!isset($row['relation_isPartOf'][0])) {
            return;
        }

        // isPartOf - expected format is "relationship_type:related_work"
        $related = $row['relation_isPartOf'][0];
        // limit by 2, because "related_work" can contain ":"
        return explode(':', $related, 2);
    }

    protected function getRelatedWorkOrderPart(array $row, $total = false) {
        $related_parts = $this->getRelatedParts($row);
        if (count($related_parts) > 1) {
            preg_match('#\((.*?)\)#', $related_parts[1], $match);
            if (isset($match[1])) {
                $related_work_order = $match[1];
                $related_work_order_parts = explode('/', $related_work_order);
                if (isset($related_work_order_parts[(int)$total])) {
                    return $related_work_order_parts[(int)$total];
                }
            }
        }
    }
}