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
use Symfony\Component\Form\Extension\DependencyInjection\DependencyInjectionExtension;
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
    public function register()
    {
        $this->app->bind('form.extensions', function ($app) {
            return [
                new DependencyInjectionExtension($app, [], []),
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

        $this->app->bind('form.type.guessers', function () {
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
            $templatePathPatterns = array_map(function ($path) {
                return "$path/form/%name%";
            }, config('view.paths'));

            return new BladeRendererEngine(
                app('view.engine.resolver')->resolve('blade'),
                new TemplateNameParser(),
                new FilesystemLoader($templatePathPatterns),
                config('form.default_themes')
            );
        });

        $this->app->singleton(FormRendererInterface::class, function () {
            $engine = app(FormRendererEngineInterface::class);
            return new FormRenderer($engine);
        });
    }
}