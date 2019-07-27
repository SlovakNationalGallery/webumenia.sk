<?php

namespace App\Filter\Generators;

class AuthorityTitleGenerator extends AbstractTitleGenerator
{
    protected $translationDomain = 'authority.filter.title_generator';

    protected $attributes = [
        'role',
        'nationality',
        'place',
        'years.from',
        'years.to',
        'first_letter',
    ];
}