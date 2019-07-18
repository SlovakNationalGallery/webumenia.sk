<?php

namespace App\Filter\Contracts;

interface TitleGenerator
{
    public function generate(Filter $filter): string;
}