<?php

namespace App\Console\Commands;

use App\ItemTranslation;
use Illuminate\Console\Command;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;

class DumpUntranslatedAttributes extends Command
{
    protected $signature = 'items:dump-untranslated-attributes
                            {--prefix= : Filter by prefix}';

    private array $attributes = [
        'work_type' => 'item.work_types',
        'technique' => 'item.techniques',
        'medium' => 'item.media',
        'object_type' => 'item.object_types',
        'topic' => 'item.topics',
    ];

    public function handle(): int
    {
        $attribute = $this->choice('Select attribute', array_keys($this->attributes));
        $locale = $this->choice('Select locale', config('translatable.locales'));
        $prefix = $this->option('prefix');

        $rows = $this->getUntranslatedAttributes($attribute, $locale, $prefix)
            ->map(fn ($count, $value) => [$value, $count]);

        $header = sprintf('%s (%s)', Str::ucfirst(trans("item.$attribute")), Str::upper($locale));
        $this->table([$header, 'Count'], $rows);

        return self::SUCCESS;
    }

    private function getUntranslatedAttributes(string $attribute, string $locale, ?string $prefix): LazyCollection
    {
        $translations = trans($this->attributes[$attribute], locale: $locale);

        return $this->getValuesWithCounts($attribute, $locale, $prefix)
            ->reject(fn ($count, $value) => in_array($value, $translations))
            ->sortDesc();
    }

    private function getValuesWithCounts(string $attribute, string $locale, ?string $prefix): LazyCollection
    {
        return ItemTranslation::query()
            ->select($attribute)
            ->where('locale', $locale)
            ->when($prefix, fn ($query) => $query->where('item_id', 'like', "$prefix%"))
            ->lazy()
            ->pluck($attribute)
            ->flatMap(fn ($value) => str($value)->split('/\s*;\s*/'))
            ->filter()
            ->countBy();
    }
}