<?php

namespace App\Filter;

use App\Filter\Contracts\Filter;

abstract class AbstractFilter implements Filter
{
    protected $filterable = [];

    public function get(string $attribute)
    {
        $camelCase = camel_case($attribute);
        return $this->{$camelCase};
    }

    protected function applyFilterable(string $attribute, &$query): void
    {
        if ($this->get($attribute) !== null) {
            $query['bool']['filter'][]['term'][$attribute] = $this->get($attribute);
        }
    }

    public function toQuery(): array
    {
        $query = [];
        foreach ($this->filterable as $filterable) {
            $this->applyFilterable($filterable, $query);
        }

        return $query;
    }
}