<?php

namespace App\Forms;

use Symfony\Component\Form\FormView;

class FormRenderer extends \Barryvdh\Form\FormRenderer
{
    public function setTheme(FormView $view, $themes) {
        $this->renderer->setTheme($view, $themes);
    }

    public function block(FormView $view, $blockName, array $variables = []) {
        return $this->renderer->renderBlock($view, $blockName, $variables);
    }

    public function label(FormView $view, $label = null, $variables = []) {
        if ($label !== null) {
            $variables['label'] = $label;
        }
        return $this->renderer->searchAndRenderBlock($view, 'label', $variables);
    }

    public function humanize($text) {
        return $this->renderer->humanize($text);
    }
}