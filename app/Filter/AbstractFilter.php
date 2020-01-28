<?php

namespace App\Filter;

use App\Filter\Contracts\Filter;

abstract class AbstractFilter implements Filter
{
    protected $filterables = [];

    public function get(string $attribute)
    {
        $camelCase = camel_case($attribute);
        return $this->{$camelCase};
    }

    public function getFilterables(): array
    {
        return $this->filterables;
    }
}