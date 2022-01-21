<?php


namespace App\Filter;

use App\Filter\Concerns\SearchRequest as SearchRequestTrait;
use App\Filter\Contracts\SearchRequest;

class ItemSearchRequest extends ItemFilter implements SearchRequest
{
    use SearchRequestTrait;
}