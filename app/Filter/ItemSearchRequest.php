<?php


namespace App\Filter;

use App\Filter\Concerns\SearchRequest as SearchRequestTrait;
use App\Filter\Contracts\SearchRequest;

class ItemSearchRequest extends ItemFilter implements SearchRequest
{
    use SearchRequestTrait;

    public function toSort(): array
    {
        $sort = [];
        if ($this->sortBy !== null) {
            $sortBy = in_array($this->sortBy, ['newest', 'oldest']) ? 'date_earliest' : $this->sortBy;
            $sortOrder = in_array($this->sortBy, ['author', 'title', 'oldest']) ? 'asc' : 'desc';

            $sort[] = [$sortBy => ['order' => $sortOrder]];
        } else {
            $sort[] = '_score';
            $sort[] = ['has_image' => ['order' => 'desc']];
            $sort[] = ['has_iip' => ['order' => 'desc']];
            $sort[] = ['updated_at' => ['order' => 'desc']];
            $sort[] = ['created_at' => ['order' => 'desc']];
        }

        return $sort;
    }
}