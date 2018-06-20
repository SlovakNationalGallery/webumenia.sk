<?php


namespace Tests\Forms;

use App\Forms\BladeRendererEngine;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Illuminate\View\View;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryBuilder;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\FormView;

class BladeRendererTest extends \Tests\TestCase
{
    /** @var FormRenderer */
    protected static $renderer;

    /** @var FormFactory */
    protected static $formFactory;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$renderer = self::createRenderer();
        self::$formFactory = self::createFormFactory();
    }

    protected static function createRenderer() {
        $root_theme_dirs = [
            __DIR__ . '/../resources/views/form',
            __DIR__ . '/../resources/views/another_form',
        ];
        $paths = [];
        foreach ($root_theme_dirs as $root_theme_dir) {
            $themes = (new Finder())->directories()->in($root_theme_dir);

            $paths[] = $root_theme_dir;
            foreach ($themes as $theme) {
                $paths[] = $theme->getRealPath();
            }
        }
        $engine = new PhpEngine();
        $engineResolver = new EngineResolver();
        $files = new Filesystem();
        $cachePath = __DIR__ . '/../cache';
        $compiler = new BladeCompiler($files, $cachePath);

        foreach (['widget', 'errors', 'label', 'row', 'rest'] as $method) {
            $compiler->directive('form' . ucfirst($method), self::createBladeDirectiveCallback($method));
        }

        $engineResolver->register('blade', function () use ($files, $compiler) {
            return new CompilerEngine($compiler);
        });
        $viewFinder = new FileViewFinder($files, []);
        $viewFinder->addNamespace('form', $paths);
        $viewFactory = new Factory($engineResolver, $viewFinder, new Dispatcher());
        $viewFactory->composer('*', function (View $view) {
            $view->with('view_name', $view->getName());
            $view->with('_form_renderer');
        });
        $rendererEngine = new BladeRendererEngine($engine, $viewFactory);
        return new FormRenderer($rendererEngine);
    }

    protected static function createFormFactory() {
        $formFactoryBuilder = new FormFactoryBuilder();
        return $formFactoryBuilder->getFormFactory();
    }

    protected static function createBladeDirectiveCallback($method) {
        return function ($expression) use ($method) {
            $output = '<?php

            $callback = function (\\Symfony\\Component\\Form\\FormView $view, array $vars) {
                echo %s->searchAndRenderBlock($view, \'%s\', $vars);
            };
            $callback(%s); ?>';

            return sprintf(
                $output,
                '$_form_renderer',
                $method,
                $expression
            );
        };
    }

    public function testThemeInheritance() {
        $formView = $this->createFormView();

        self::$renderer->setTheme($formView, 'theme_inheritance_third');
        $rendered = self::$renderer->searchAndRenderBlock($formView, 'widget');

        $expectedLines = [
            'theme_inheritance_third.form_widget',
            'theme_inheritance_first.form_widget',
        ];

        $this->assertLinesEqual($expectedLines, $rendered);
    }

    public function testThemeInheritanceWhenIncluded() {
        $formView = $this->createFormView();

        self::$renderer->setTheme($formView, 'theme_inheritance_included_second');
        $rendered = self::$renderer->searchAndRenderBlock($formView, 'row');

        $expectedLines = [
            'theme_inheritance_included_second.form_row',
            'theme_inheritance_included_second.form_widget',
            'theme_inheritance_included_first.form_widget',
        ];

        $this->assertLinesEqual($expectedLines, $rendered);
    }

    public function testFallbackToAnotherForm() {
        $formView = $this->createFormView();

        self::$renderer->setTheme($formView, ['fallback_to_another_form']);
        $rendered = self::$renderer->searchAndRenderBlock($formView, 'row');

        $expectedLines = [
            'form.fallback_to_another_form.form_row',
            'another_form.default.form_widget',
        ];

        $this->assertLinesEqual($expectedLines, $rendered);
    }

    protected function createFormView() {
        $form = self::$formFactory->create();
        return $form->createView();
    }

    protected function assertLinesEqual(array $expected, $actual) {
        $this->assertEquals(implode(PHP_EOL, $expected), $actual);
    }
}