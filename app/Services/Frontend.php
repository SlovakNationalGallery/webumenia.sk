<?php

namespace App\Services;

use App\Enums\FrontendEnum;

class Frontend
{
    public function __construct(private FrontendEnum $current)
    {
    }

    public function set(FrontendEnum $frontend): void
    {
        $this->current = $frontend;
    }

    public function get(): FrontendEnum
    {
        return $this->current;
    }
}