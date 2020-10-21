<?php

namespace App\Translation;

use Illuminate\Translation\Translator;

class DomainFallbackTranslator extends Translator
{
    protected function getLine($namespace, $group, $locale, $item, array $replace)
    {
        $path = $this->path($item);
        $head = end($path);

        while (array_pop($path) !== null) {
            $line = parent::getLine($namespace, $group, $locale, $this->key($path, $head), $replace);
            if ($line !== null) {
                return $line;
            }
        }

        return parent::getLine($namespace, $group, $locale, $item, $replace);
    }

    protected function key(array $path, string $head): string
    {
        $path[] = $head;
        return implode('.', $path);
    }

    protected function path(?string $item): array
    {
        return explode('.', $item);
    }
}