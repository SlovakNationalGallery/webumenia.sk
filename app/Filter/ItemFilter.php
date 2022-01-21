<?php

namespace App\Filter;

use App\IntegerRange;
use Primal\Color\Color;

class ItemFilter extends AbstractFilter
{
    /** @var IntegerRange|null */
    protected $years;

    protected $author;

    protected $gallery;

    protected $credit;

    protected $workType;

    protected $objectType;

    protected $topic;

    protected $tag;

    protected $technique;

    protected $medium;

    protected $relatedWork;

    protected $search;

    protected $hasImage;

    protected $hasIip;

    protected $isFree;

    protected $isForReproduction;

    protected $hasText;

    /** @var Color|null */
    protected $color;

    protected $filterables = [
        'author',
        'gallery',
        'credit',
        'work_type',
        'object_type',
        'topic',
        'tag',
        'technique',
        'medium',
        'related_work',
        'has_image',
        'has_iip',
        'has_text',
        'is_free',
        'is_for_reproduction',
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

    public function getCredit(): ?string
    {
        return $this->credit;
    }

    public function setCredit(?string $credit): self
    {
        $this->credit = $credit;
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

    public function getObjectType(): ?string
    {
        return $this->objectType;
    }

    public function setObjectType(?string $objectType): self
    {
        $this->objectType = $objectType;
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

    public function getIsForReproduction(): ?bool
    {
        return $this->isForReproduction;
    }

    public function setIsForReproduction(?bool $isForReproduction): self
    {
        $this->isForReproduction = $isForReproduction;
        return $this;
    }

    public function getHasText(): ?bool
    {
        return $this->hasText;
    }

    public function setHasText(?bool $hasText): self
    {
        $this->hasText = $hasText;
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
