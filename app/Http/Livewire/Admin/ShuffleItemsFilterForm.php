<?php

namespace App\Http\Livewire\Admin;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Component;

class ShuffleItemsFilterForm extends Component
{
    public array $filters;
    public string $locale;

    private static $recognizedAttributes = [
        'author',
        'work_type',
        'topic',
        'object_type',
        'place',
        'medium',
        'tag',
        'gallery',
        'technique',
    ];

    public function mount()
    {
        // Add a random ID to each element to allow list management
        $this->filters = collect($this->filters)
            ->map(fn($filter) => array_merge($filter, ['_id' => Str::uuid()]))
            ->toArray();
    }

    public function getSelectableAttributes(int $filterIndex)
    {
        return array_keys($this->getAttributesFromUrl($this->filters[$filterIndex]['url']));
    }

    public function fillLabelWithUrlValue(int $filterIndex, int $attributeIndex)
    {
        $filterUrl = $this->filters[$filterIndex]['url'];

        $attributeName = Arr::get($this->filters, "$filterIndex.attributes.$attributeIndex.name");
        $attributeDefault = $this->getAttributesFromUrl($filterUrl)[$attributeName];

        Arr::set(
            $this->filters,
            "$filterIndex.attributes.$attributeIndex.label",
            $attributeDefault
        );
    }

    private function getAttributesFromUrl(string $url): array
    {
        $parsedUrl = parse_url($url);

        if (!Arr::has($parsedUrl, 'query')) {
            return [];
        }

        $urlQueryParams = [];
        parse_str($parsedUrl['query'], $urlQueryParams);

        return Arr::only($urlQueryParams, self::$recognizedAttributes);
    }

    public function add()
    {
        $this->filters[] = ['_id' => Str::uuid(), 'url' => '', 'attributes' => [[], [], []]];
    }

    public function delete(string $_id)
    {
        $this->filters = collect($this->filters)
            ->reject(fn($filter) => $filter['_id'] === $_id)
            ->toArray();
    }

    public function render()
    {
        return view('livewire.admin.shuffle-items-filter-form');
    }
}
