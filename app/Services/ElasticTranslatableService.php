<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;


class ElasticTranslatableService
{
    protected $separator = '_';
    protected $index_prefix;

    public function __construct($index)
    {
        // get index prefix (drop locale suffix if is set)
        // e.g. webumenia_sk -> webumenia
        $index_name_parts = explode($this->separator, $index);
        $available_locales = config('translatable.locales');;

        if (in_array(end($index_name_parts), $available_locales)) {
            array_pop($index_name_parts);
        }

        $this->index_prefix = implode($this->separator, $index_name_parts);
    }

    public function getIndexForLocale($locale)
    {
        return $this->index_prefix . $this->separator . $locale;
    }

    public function getAllIndexes()
    {
        $all = [];
        foreach (config('translatable.locales') as $locale) {
            $all[] = $this->getIndexForLocale($locale);
        }
        return $all;
    }

    public function setCurrentIndex($locale)
    {
        return config(['bouncy.index' => $this->getIndexForLocale($locale)]);
    }

    public function getAnalyzerNameForSynonyms()
    {
        return str_slug(\LaravelLocalization::getCurrentLocaleNative()) . '_synonyms';
    }

}