<?php

namespace App\Contracts;

interface IndexableModel
{
    public function getIndexedData($locale);
}