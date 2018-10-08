<?php

namespace App\Harvest\Mappers;

abstract class AbstractModelMapper extends AbstractMapper
{
    /** @var string */
    protected $modelClass;

    public function __construct() {
        $model = new $this->modelClass();
        if ($model->translatedAttributes) {
            $this->translatedAttributes = $model->translatedAttributes;
        }
    }
}