<?php

namespace App\Filter;

use App\Color;
use App\IntegerRange;

class ItemFilter extends AbstractFilter
{
    /** @var IntegerRange|null */
    protected $years;

    protected $author;

    protected $gallery;

    protected $workType;

    protected $topic;

    protected $tag;

    protected $technique;

    protected $medium;

    protected $relatedWork;

    protected $search;

    protected $hasImage;

    protected $hasIip;

    protected $isFree;

    /** @var Color|null */
    protected $color;

    protected $filterable = [
        'author',
        'gallery',
        'work_type',
        'topic',
        'tag',
        'technique',
        'medium',
        'related_work',
        'has_image',
        'has_iip',
        'is_free',
    ];

    public function setYearTo(?int $yearTo): self
    {
        $this->yearTo = $yearTo;
        return $this;
    }

    public function getYears(): ?IntegerRange
    {
        return $this->years;
    }

    public function setYears(?IntegerRange $years): self
    {
        $this->years = $years;
        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;
        return $this;
    }

    public function getGallery(): ?string
    {
        return $this->gallery;
    }

    public function setGallery(?string $gallery): self
    {
        $this->gallery = $gallery;
        return $this;
    }

    public function getWorkType(): ?string
    {
        return $this->workType;
    }

    public function setWorkType(?string $workType): self
    {
        $this->workType = $workType;
        return $this;
    }

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function setTopic(?string $topic): self
    {
        $this->topic = $topic;
        return $this;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(?string $tag): self
    {
        $this->tag = $tag;
        return $this;
    }

    public function getTechnique(): ?string
    {
        return $this->technique;
    }

    public function setTechnique(?string $technique): self
    {
        $this->technique = $technique;
        return $this;
    }

    public function getMedium(): ?string
    {
        return $this->medium;
    }

    public function setMedium(?string $medium): self
    {
        $this->medium = $medium;
        return $this;
    }

    public function getRelatedWork(): ?string
    {
        return $this->relatedWork;
    }

    public function setRelatedWork(?string $relatedWork): self
    {
        $this->relatedWork = $relatedWork;
        return $this;
    }

    public function getHasImage(): ?bool
    {
        return $this->hasImage;
    }

    public function setHasImage(?bool $hasImage): self
    {
        $this->hasImage = $hasImage;
        return $this;
    }

    public function getHasIip(): ?bool
    {
        return $this->hasIip;
    }

    public function setHasIip(?bool $hasIip): self
    {
        $this->hasIip = $hasIip;
        return $this;
    }

    public function getIsFree(): ?bool
    {
        return $this->isFree;
    }

    public function setIsFree(?bool $isFree): self
    {
        $this->isFree = $isFree;
        return $this;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): self
    {
        $this->search = $search;
        return $this;
    }

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function setColor(?Color $color): self
    {
        $this->color = $color;
        return $this;
    }

    public function toQuery(): array
    {
        $query = parent::toQuery();
        $this->applySearch($query);
        $this->applyYears($query);
//        $this->applyColor($query);
        return $query;
    }

    protected function applySearch(array &$query)
    {
        if ($this->search === null) {
            return;
        }

        $should_match = [
            'identifier' => [
                'query' => $this->search,
                'boost' => 10,
            ],
            'author.folded' => [
                'query' => $this->search,
                'boost' => 5,
            ],
            'title' => $this->search,
            'title.folded' => $this->search,
            'title.stemmed' => [
                'query' => $this->search,
                'analyzer' => 'synonyms_analyzer'
            ],
            'tag.folded' => $this->search,
            'tag.stemmed' => $this->search,
            'place.folded' => $this->search,
            'description' => $this->search,
            'description.stemmed' => [
                'query' => $this->search,
                'analyzer' => 'synonyms_analyzer',
                'boost' => 0.5,
            ],
        ];

        $should = [];
        foreach ($should_match as $key => $match) {
            $should[] = ['match' => [$key => $match]];
        }

        $query['bool']['should'] = $should;
        $query['bool']['minimum_should_match'] = 1;
    }

    protected function applyYears(array &$query)
    {
        if ($this->years && $this->years->getFrom() !== null) {
            $query['bool']['filter'][]['range']['date_latest']['gte'] = $this->years->getFrom();
        }

        if ($this->years && $this->years->getTo() !== null) {
            $query['bool']['filter'][]['range']['date_earliest']['lte'] = $this->years->getTo();
        }
    }

    protected function applyColor(array &$query)
    {
        if ($this->color) {
            $query['bool']['should'][]['descriptor'] = [
                'color_descriptor' => [
                    'hash' => 'LSH',
                    'descriptor' => $this->color->getDescriptor(),
                ]
            ];
        }
    }

    public function __get($name)
    {
        if ($name === 'years-range') {
            return $this->getYears();
        }

        throw new \RuntimeException();
    }

    public function __set($name, $value)
    {
        if ($name === 'years-range') {
            $this->setYears($value);
        } else {
            throw new \RuntimeException();
        }
    }
}