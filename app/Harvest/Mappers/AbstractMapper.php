<?php

namespace App\Harvest\Mappers;

use Illuminate\Support\Str;

abstract class AbstractMapper
{
    /** @var string[] */
    protected $localeToLangMap = [
        'sk' => 'sk',
        'en' => 'en',
        'cs' => 'cs',
    ];

    protected $translatedAttributes = [];

    /** @var string */
    protected $modelClass;

    public function __construct() {
        if ($this->modelClass) {
            $model = new $this->modelClass();
            if ($model->translatedAttributes) {
                $this->translatedAttributes = $model->translatedAttributes;
            }
        }
    }

    /**
     * @param array $row
     * @return array
     */
    public function map(array $row) {
        $mapped = [];

        $methods = get_class_methods($this);
        $methods = array_filter($methods, function ($method) {
            return Str::startsWith($method, 'map') && $method !== 'map';
        });

        foreach ($methods as $method) {
            $key = Str::snake(Str::after($method, 'map'));

            if (in_array($key, $this->translatedAttributes)) {
                foreach ($this->localeToLangMap as $locale => $lang) {
                    $value = $this->$method($row, $locale);
                    $this->setMapped($mapped, "$key:$locale", $value);
                }
            } else {
                $value = $this->$method($row);
                $this->setMapped($mapped, $key, $value);
            }
        }

        return $mapped;
    }

    /**
     * @param array $mapped
     * @param string $key
     * @param mixed $value
     */
    protected function setMapped(array &$mapped, $key, $value) {
        // dont serialize roles - they are already casted as array
        if (is_array($value) && !str_contains($key, 'roles:')) {
            $value = $this->serialize($value);
        }

        $mapped[$key] = $value;
    }

    /**
     * @param array $row
     * @param string $name
     * @param string $locale
     */
    protected function getLocalized(array $row, $name, $locale) {
        $localized = [];
        foreach ($row[$name] as $r) {
            if (!isset($r['lang'][0])) {
                continue;
            }

            if ($r['lang'][0] == $this->localeToLangMap[$locale]) {
                $localized = array_merge($localized, $r[$name]);
            }
        }

        return $localized;
    }

    /**
     * @param string $string
     * @param string $locale
     * @param string $delimiter
     * @return string
     */
    protected function chooseTranslation($string, $locale, $delimiter = '/') {
        $locales = array_keys($this->localeToLangMap);
        $index = array_search($locale, $locales);

        if ($index === false) {
            return;
        }

        $parts = explode($delimiter, $string);
        return isset($parts[$index]) ? $parts[$index] : null;
    }

    /**
     * @param array $attribute
     * @param string $delimiter
     * @return string
     */
    protected function serialize(array $attribute, $delimiter = '; ') {
        return implode($delimiter, $attribute);
    }

    /**
     * @param string $string
     * @param string $delimiter
     * @return string
     */
    protected function parseId($string, $delimiter = ':') {
        $exploded = explode($delimiter, $string);
        return end($exploded);
    }
}
