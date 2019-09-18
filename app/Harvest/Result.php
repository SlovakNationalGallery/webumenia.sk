<?php

namespace App\Harvest;

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

    /** @var arraystring[] */
    protected $errors = [];

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

    public function getErrors() {
        return $this->errors;
    }

    public function addError($id, $message) {
        $this->errors[$id] = $message;
    }

    public function getErrorMessages() {
        $messages = [];
        foreach ($this->errors as $id => $message) {
            $messages[] = trans('harvest.status_messages.error', [
                'id' => $id,
                'message' => $message,
            ]);
        }

        return $messages;
    }
}