<?php

use Symfony\Component\Form\ChoiceList\View\ChoiceView;

function is_selected_choice(ChoiceView $choice, $selectedValue)
{
    if (is_array($selectedValue)) {
        return in_array($choice->value, $selectedValue, true);
    }

    return $choice->value === $selectedValue;
}

function parent($view_name)
{
    $pattern = sprintf('/%s::(.*)\.(.*)/', config('form.template_namespace'));
    if (!preg_match($pattern, $view_name, $matches)) {
        throw new \Exception;
    }

    $theme = $matches[1];
    $block = $matches[2];

    $hints = view()->getFinder()->getHints();
    $template_namespace = config('form.template_namespace');
    $theme_dirs = $hints[$template_namespace];

    $theme_locator = function($theme) use ($theme_dirs) {
        foreach ($theme_dirs as $theme_dir) {
            if (basename($theme_dir) == $theme && is_dir($theme_dir)) {
                return $theme_dir;
            }
        }
    };

    $theme_dir = $theme_locator($theme);
    $theme = basename(dirname($theme_dir));

    while ($theme_dir = $theme_locator($theme)) {
        $view = sprintf('%s::%s.%s', config('form.template_namespace'), $theme, $block);
        if (view()->exists($view)) {
            return $view;
        }

        $theme = basename(dirname($theme_dir));
    }
}