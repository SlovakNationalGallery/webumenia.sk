<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class FormRenderer extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return \App\Forms\FormRenderer::class;
    }
}