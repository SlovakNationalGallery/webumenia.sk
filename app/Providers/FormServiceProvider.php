<?php

namespace App\Providers;

use App\Forms\BladeRendererEngine;
use Barryvdh\Form\Extension\EloquentExtension;
use Barryvdh\Form\Extension\FormDefaultsTypeExtension;
use Barryvdh\Form\Extension\FormValidatorExtension;
use Barryvdh\Form\Extension\Http\HttpExtension;
use Barryvdh\Form\Extension\SessionExtension;
use Barryvdh\Form\Extension\Validation\ValidationTypeExtension;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\FormRendererEngineInterface;
use Symfony\Component\Form\FormRendererInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\ResolvedFormTypeFactory;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\TemplateNameParser;

class FormServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $root_theme_dirs = (array)config('form.themes_dir');
        $paths = [];
        foreach ($root_theme_dirs as $root_theme_dir) {
            $themes = (new Finder())->directories()->in($root_theme_dir);

            $paths[] = $root_theme_dir;
            foreach ($themes as $theme) {
                $paths[] = $theme->getRealPath();
            }
        }

        app('view')->getFinder()->addNamespace(
            config('form.template_namespace'),
            $paths
        );

        $this->registerViewComposer();
    }

    public function register()
    {
        $this->app->bind('form.extensions', function ($app) {
            return [
                new SessionExtension(),
                new HttpExtension(),
                new EloquentExtension(),
                new FormValidatorExtension(),
            ];
        });

        $this->app->bind('form.type.extensions', function ($app) {
            return [
                new FormDefaultsTypeExtension(config('form.defaults', [])),
                new ValidationTypeExtension($app['validator']),
            ];
        });

        $this->app->bind('form.type.guessers', function ($app) {
            return [];
        });

        $this->app->bind('form.resolved_type_factory', function () {
            return new ResolvedFormTypeFactory();
        });

        $this->app->singleton(FormFactoryInterface::class, function ($app) {
            return Forms::createFormFactoryBuilder()
                ->addExtensions($app['form.extensions'])
                ->addTypeExtensions($app['form.type.extensions'])
                ->addTypeGuessers($app['form.type.guessers'])
                ->setResolvedTypeFactory($app['form.resolved_type_factory'])
                ->getFormFactory();
        });

        $this->app->singleton(FormRendererEngineInterface::class, function () {
            return new BladeRendererEngine(
                app('view.engine.resolver')->resolve('blade'),
                app('view'),
                (array)config('form.default_theme')
            );
        });

        $this->app->singleton(FormRendererInterface::class, function () {
            $engine = app(FormRendererEngineInterface::class);
            return new FormRenderer($engine);
        });
    }

    protected function registerViewComposer()
    {
        view()->composer('*', function (View $view) {
            foreach ($view->getData() as $key => $value) {
                if ($value instanceof Form) {
                    $view->with($key, $value->createView());
                }
            }

            // needed for template inheritance
            $view->with('view_name', $view->getName());
        });
    }
}