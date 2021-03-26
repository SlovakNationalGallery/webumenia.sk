<?php

namespace Tests\Translation;

use App\Translation\DomainFallbackTranslator;
use Illuminate\Contracts\Translation\Loader;
use Illuminate\Translation\ArrayLoader;
use Tests\TestCase;

class DomainFallbackTranslatorTest extends TestCase
{
    public function testKeyPathExists()
    {
        $loaderMock = $this->createMock(Loader::class);
        $loaderMock->method('load')
            ->with('sk', 'path')
            ->willReturn([
                'to' => [
                    'key' => 'translation'
                ]
            ]);
        $translator = new DomainFallbackTranslator($loaderMock, 'sk');

        $translation = $translator->get('path.to.key');
        $this->assertEquals('translation', $translation);
    }

    public function testDomainFallbackKeyPathExists()
    {
        $loaderMock = $this->createMock(Loader::class);
        $loaderMock->method('load')
            ->with('sk', 'path')
            ->willReturn([
                'key' => 'translation',
            ]);
        $translator = new DomainFallbackTranslator($loaderMock, 'sk');

        $translation = $translator->get('path.to.key');
        $this->assertEquals('translation', $translation);
    }

    public function testLocaleFallbackExists()
    {
        $loaderMock = $this->createMock(Loader::class);
        $loaderMock->expects($this->at(0))
            ->method('load')
            ->with('sk', 'path')
            ->willReturn([]);
        $loaderMock->expects($this->at(1))
            ->method('load')
            ->with('en', 'path')
            ->willReturn([
                'to' => [
                    'key' => 'translation'
                ]
            ]);
        $translator = new DomainFallbackTranslator($loaderMock, 'sk');
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
