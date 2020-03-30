<?php

namespace App\Filter;

use Primal\Color\Color;

class CollectionFilter extends AbstractFilter
{
    protected $author;

    protected $type;

    protected $filterables = [
        'author',
        'type'
    ];

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }
}
