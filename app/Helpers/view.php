<?php

use Symfony\Component\Form\ChoiceList\View\ChoiceView;

function is_selected_choice(ChoiceView $choice, $selectedValue)
{
    return in_array($choice->value, (array)$selectedValue, true);
}

function is_empty($value)
{
    if ($value instanceof Countable) {
        return 0 == count($value);
    }

    if (is_object($value) && method_exists($value, '__toString')) {
        return '' === (string) $value;
    }

    return '' === $value || false === $value || null === $value || array() === $value;
}