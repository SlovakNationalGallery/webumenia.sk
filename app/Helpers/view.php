<?php

use Symfony\Component\Form\ChoiceList\View\ChoiceView;

function is_selected_choice(ChoiceView $choice, $selectedValue)
{
    return in_array($choice->value, (array)$selectedValue, true);
}