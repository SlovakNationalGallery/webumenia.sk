<?php

namespace App\Concerns;

trait DelegatesAttributes
{
    public function getAttribute($key)
    {
        if (key_exists($key, $this->delegated)) {
            return $this->{$this->delegated[$key]}->{$key};
        }

        return parent::getAttribute($key);
    }

    protected function getDelegatedAttributes()
    {
        return $this->delegated ?? [];
    }
}
