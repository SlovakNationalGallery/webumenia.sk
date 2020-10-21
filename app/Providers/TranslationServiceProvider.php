<?php

namespace App\Providers;

use App\Translation\DomainFallbackTranslator;

class TranslationServiceProvider extends \Illuminate\Translation\TranslationServiceProvider
{
    public function register()
    {
        parent::register();
        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];
            // dd($loader);

            // When registering the translator component, we'll need to set the default
            // locale as well as the fallback locale. So, we'll grab the application
            // configuration so we can easily get both of these values from there.
            $locale = $app['config']['app.locale'];

            $trans = new \Illuminate\Translation\Translator($loader, $locale);
            // $trans = new DomainFallbackTranslator($loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });
    }
}
