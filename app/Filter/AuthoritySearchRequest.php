<?php

namespace App\Filter;

use App\Filter\Concerns\SearchRequest as SearchRequestTrait;
use App\Filter\Contracts\SearchRequest;

class AuthoritySearchRequest extends AuthorityFilter implements SearchRequest
{
    use SearchRequestTrait;
}