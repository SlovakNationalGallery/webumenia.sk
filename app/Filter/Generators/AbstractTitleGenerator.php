<?php

namespace App\Filter\Generators;

use App\IntegerRange;
use App\Filter\AbstractFilter;
use App\Filter\Contracts\Filter;
use App\Filter\Contracts\TitleGenerator;
use Illuminate\Contracts\Translation\Translator;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccessor;

abstract class AbstractTitleGenerator implements TitleGenerator
{
    protected $translator;

    protected $propertyAccessor;

    protected $translationDomain;

    protected $separator = " \u{2022} ";

    protected $attributes = [];

    public function __construct(Translator $translator, PropertyAccessor $propertyAccessor)
    {
        $this->translator = $translator;
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @param string|null $locale
     * @return string
     */
    public function translateAttribute(string $attribute, $value = null, string $locale = null)
    {
        $key = $this->translationDomain ? "$this->translationDomain.$attribute" : $attribute;
        if ($value instanceof IntegerRange) return $this->translator->get(
            $key,
            ['from' => $value->getFrom(), 'to' => $value->getTo()],
            $locale
        );
        return $this->translator->get($key, ['value' => $value], $locale);
    }

    /**
     * @param AbstractFilter $filter
     * @param string|null $locale
     * @return string
     */
    public function generate(Filter $filter, string $locale = null): string
    {
        $parts = [];
        foreach ($this->attributes as $attribute) {
            try {
                $value = $this->propertyAccessor->getValue($filter, $attribute);
            } catch (UnexpectedTypeException $e) {
                continue;
            }

            if ($value !== null) {
                $parts[] = $this->translateAttribute($attribute, $value);
            }
        }

        return implode($this->separator, $parts);
    }
}
