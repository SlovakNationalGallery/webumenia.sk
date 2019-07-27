<?php

namespace App\Filter;

use App\Filter\Concerns\SearchRequest as SearchRequestTrait;
use App\Filter\Contracts\SearchRequest;

class AuthoritySearchRequest extends AuthorityFilter implements SearchRequest
{
    use SearchRequestTrait;

    public function toSort(): array
    {
        $sort = [];
        if ($this->sortBy !== null) {
            $sortOrder = $this->sortBy === 'name' ? 'asc' : 'desc';
            $sort[] = [$this->sortBy => ['order' => $sortOrder]];
        } else {
            $sort[] = ['items_with_images_count' => ['order' => 'desc']];
        }

        return $sort;
    }
}