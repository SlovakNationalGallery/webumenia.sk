<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;


class ElasticTranslatableService
{
    protected $separator = '_';
    protected $index_prefix;

    public function __construct($index)
    {
        $this->index_prefix = strtok($index, $this->separator); // webumenia_sk -> webumenia
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