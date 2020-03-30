<?php


namespace App\Filter;

use App\Filter\Concerns\SearchRequest as SearchRequestTrait;
use App\Filter\Contracts\SearchRequest;

class CollectionSearchRequest extends CollectionFilter implements SearchRequest
{
    use SearchRequestTrait;
}