<?php

namespace App\Harvest\Mappers;

use App\AuthorityEvent;

class AuthorityEventMapper extends AbstractModelMapper
{
    protected $modelClass = AuthorityEvent::class;

    public function mapEvent(array $row, $locale) {
        $event = $this->serialize($row['event']);
        return $this->chooseTranslation($event, $locale);
    }

    public function mapPlace(array $row) {
        $place = $this->serialize($row['place']);
        $parts = explode('/', $place);
        return $parts[0];
    }

    public function mapStartDate(array $row) {
        return $row['start_date'][0] ?: null;
    }

    public function mapEndDate(array $row) {
        return $row['end_date'][0] ?: null;
    }

    public function mapPrefered() {
        return false;
    }
}