<?php

namespace App\Filter\Generators;

class ItemTitleGenerator extends AbstractTitleGenerator
{
    protected $translationDomain = 'item.filter.title_generator';

    protected $attributes = [
        'search',
        'author',
        'work_type',
        'tag',
        'gallery',
        'topic',
        'medium',
        'technique',
        'has_image',
        'has_iip',
        'is_free',
        'related_work',
        'years.from',
        'years.to',
    ];

    public function translateAttribute(string $attribute, $value = null, string $locale = null)
    {
        $value = $attribute === 'author' ? formatName($value) : $value;
        return parent::translateAttribute($attribute, $value, $locale);
    }
}