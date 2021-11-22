<?php

namespace App\Services\MissingPageRedirector;

use App\Redirect;
use Spatie\MissingPageRedirector\Redirector\Redirector;
use Symfony\Component\HttpFoundation\Request;

class DatabaseRedirector implements Redirector
{
    public function getRedirectsFor(Request $request): array
    {
        return Redirect::enabled()->get(['source_url', 'target_url'])->flatMap(function ($redirect) {
            return [$redirect->source_url => $redirect->target_url];
        })->toArray();
    }
}
