<?php

namespace App\Filter\Concerns;

use App\Filter\Contracts\SearchRequest as SearchRequestInterface;

trait SearchRequest
{
    protected $sortBy;

    protected $size;

    protected $from;

    /**
     * @return string|null
     */
    public function getSortBy(): ?string
    {
        return $this->sortBy;
    }

    /**
     * @param string|null $sortBy
     * @return $this
     */
    public function setSortBy(?string $sortBy): SearchRequestInterface
    {
        $this->sortBy = $sortBy;
        return $this;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     * @return $this
     */
    public function setSize(int $size): SearchRequestInterface
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return int
     */
    public function getFrom(): int
    {
        return $this->from;
    }

    /**
     * @param int $from
     * @return $this
     */
    public function setFrom(int $from): SearchRequestInterface
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return array
     */
    public function toBody(): array
    {
        $query = $this->toQuery();
        $body = $query ? ['query' => $query] : [];
        $sort = $this->toSort();
        if ($sort) {
            $body['sort'] = $this->toSort();
        }
        if ($this->from !== null) {
            $body['from'] = $this->from;
        }
        if ($this->size !== null) {
            $body['size'] = $this->size;
        }
        return $body;
    }
}