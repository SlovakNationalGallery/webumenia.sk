<?php

namespace App\Services\MissingPageRedirector;

use App\Redirect;
use Illuminate\Support\Facades\Cache;
use Spatie\MissingPageRedirector\Redirector\Redirector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DatabaseRedirector implements Redirector
{
    public function getRedirectsFor(Request $request): array
    {
        return Cache::rememberForever('redirects', function () {
            return Redirect::enabled()->get(['source_url', 'target_url'])->flatMap(function ($redirect) {
                return [
                    $redirect->source_url => [
                        $redirect->target_url,
                        Response::HTTP_FOUND
                    ]
                ];
            })->toArray();
        });
    }
}
