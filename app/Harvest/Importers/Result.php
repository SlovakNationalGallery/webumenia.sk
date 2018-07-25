<?php

namespace App\Harvest\Importers;

class Result
{
    /** @var int */
    protected $inserted = 0;

    /** @var int */
    protected $updated = 0;

    /** @var int */
    protected $skipped = 0;

    /** @var int */
    protected $deleted = 0;

    public function getInserted() {
        return $this->inserted;
    }

    public function getUpdated() {
        return $this->updated;
    }

    public function getSkipped() {
        return $this->skipped;
    }

    public function getDeleted() {
        return $this->deleted;
    }

    public function getTotal() {
        return $this->inserted + $this->updated + $this->skipped + $this->deleted;
    }

    public function incrementInserted() {
        $this->inserted++;
    }

    public function incrementUpdated() {
        $this->updated++;
    }

    public function incrementSkipped() {
        $this->skipped++;
    }

    public function incrementDeleted() {
        $this->deleted++;
    }
}