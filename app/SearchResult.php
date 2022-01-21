<?php

namespace App;

class SearchResult
{
    /** @var \Illuminate\Support\Collection */
    protected $collection;

    /** @var int */
    protected $total;

    public function __construct(\Illuminate\Support\Collection $collection, $total) {
        $this->collection = $collection;
        $this->total = $total;
    }

    public function getCollection() {
        return $this->collection;
    }

    public function getTotal() {
        return $this->total;
    }
}