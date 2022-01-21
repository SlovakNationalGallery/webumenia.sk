<?php

namespace App\Filter;

use App\Filter\Contracts\Filter;
use Illuminate\Support\Str;

abstract class AbstractFilter implements Filter
{
    protected $filterables = [];

    public function get(string $attribute)
    {
        $camelCase = Str::camel($attribute);
        return $this->{$camelCase};
    }

    public function getFilterables(): array
    {
        return $this->filterables;
    }
}
