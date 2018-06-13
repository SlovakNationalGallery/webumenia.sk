<?php

namespace App\Templating;

class TemplateNameParser extends \Symfony\Component\Templating\TemplateNameParser
{
    public function parse($name)
    {
        $reference = parent::parse($name);

        $name = $reference->get('name');
        $reference->set('name', str_replace(':', DIRECTORY_SEPARATOR, $name));

        return $reference;
    }
}
