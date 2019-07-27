<?php

namespace App\Filter\Contracts;

interface SearchRequest extends Filter
{
    public function getSortBy(): ?string;

    public function setSortBy(?string $sortBy): SearchRequest;

    public function getSize(): int;

    public function setSize(int $size): self;

    public function getFrom(): int;

    public function setFrom(int $from): self;

    public function toBody(): array;

    public function toSort(): array;
}