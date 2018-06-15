<?php


namespace Tests\Forms;

class BladeRendererTest extends \Tests\TestCase
{
    public function setUp() {
        $this->bootingCallbacks[] = function ($app) {
            $app->config->set('form.themes_dir', base_path('tests/resources/views/form'));
        };
        parent::setUp();
    }

    public function testSimple() {
        $formView = $this->createFormView();

        \FormRenderer::setTheme($formView, 'first');
        $rendered = \FormRenderer::widget($formView);

        $this->assertEquals('first.form_widget', $rendered);
    }

    public function testThemeInheritance() {
        $formView = $this->createFormView();

        \FormRenderer::setTheme($formView, 'third');
        $rendered = \FormRenderer::widget($formView);

        $this->assertEquals("third.form_widget\nfirst.form_widget", $rendered);
    }

    protected function createFormView() {
        $form = \FormFactory::create();
        return $form->createView();
    }
}