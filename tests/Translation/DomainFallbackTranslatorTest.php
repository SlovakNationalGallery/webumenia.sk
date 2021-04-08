<?php

namespace Tests\Translation;

use App\Translation\DomainFallbackTranslator;
use Illuminate\Translation\ArrayLoader;
use Tests\TestCase;

class DomainFallbackTranslatorTest extends TestCase
{
    public function testKeyPathExists()
    {
        $loader = (new ArrayLoader)
        ->addMessages('sk', 'path', [
            'to' => [
                'key' => 'translation'
            ]
        ]);
        $translator = new DomainFallbackTranslator($loader, 'sk');

        $translation = $translator->get('path.to.key');
        $this->assertEquals('translation', $translation);
    }

    public function testDomainFallbackKeyPathExists()
    {
        $loader = (new ArrayLoader)
            ->addMessages('sk', 'path', [
                'key' => 'translation'
            ]);
        $translator = new DomainFallbackTranslator($loader, 'sk');

        $translation = $translator->get('path.to.key');
        $this->assertEquals('translation', $translation);
    }

    public function testLocaleFallbackExists()
    {
        $loader = (new ArrayLoader)
            ->addMessages('sk', 'path', [])
            ->addMessages('en', 'path', [
                'to' => [
                    'key' => 'translation'
                ]
            ]);
        $translator = new DomainFallbackTranslator($loader, 'sk');
        $translator->setFallback('en');

        $translation = $translator->get('path.to.key');
        $this->assertEquals('translation', $translation);
    }

    public function testDomainAndLocaleFallbackExists()
    {
        $loader = (new ArrayLoader)
            ->addMessages('sk', 'path', [
                'key' => 'sk_translation',
            ])
            ->addMessages('en', 'path', [
                'to' => [
                    'key' => 'en_translation'
                ]
            ]);
        $translator = new DomainFallbackTranslator($loader, 'sk');
        $translator->setFallback('en');

        $translation = $translator->get('path.to.key');
        $this->assertEquals('sk_translation', $translation);
    }
}
