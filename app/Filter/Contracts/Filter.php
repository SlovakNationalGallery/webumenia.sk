<?php

namespace App\Filter\Contracts;

interface Filter
{
    public function get(string $attribute);

    public function toQuery(): array;
}