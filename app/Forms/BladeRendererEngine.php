<?php

namespace App\Forms;

use Illuminate\Contracts\View\Engine;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Symfony\Component\Form\AbstractRendererEngine;
use Symfony\Component\Form\FormView;
use Symfony\Component\Templating\Loader\LoaderInterface;
use Symfony\Component\Templating\TemplateNameParserInterface;

class BladeRendererEngine extends AbstractRendererEngine
{
    /** @var Engine */
    protected $engine;

    /** @var TemplateNameParserInterface */
    protected $parser;

    /** @var LoaderInterface */
    protected $loader;

    public function __construct(
        Engine $engine,
        TemplateNameParserInterface $parser,
        LoaderInterface $loader,
        array $defaultThemes = array()
    ) {
        parent::__construct($defaultThemes);
        $this->engine = $engine;
        $this->parser = $parser;
        $this->loader = $loader;
    }

    public function renderBlock(FormView $view, $resource, $blockName, array $variables = array())
    {
        $cacheKey = $view->vars[self::CACHE_KEY_VAR];

        $variables['__env'] = view();
        return $this->engine->get($this->resources[$cacheKey][$blockName], $variables);
    }

    /**
     * Loads the cache with the resource for a given block name.
     *
     * This implementation tries to load as few blocks as possible, since each block
     * is represented by a template on the file system.
     *
     * @see getResourceForBlock()
     *
     * @param string   $cacheKey  The cache key of the form view
     * @param FormView $view      The form view for finding the applying themes
     * @param string   $blockName The name of the block to load
     *
     * @return bool True if the resource could be loaded, false otherwise
     */
    protected function loadResourceForBlockName($cacheKey, FormView $view, $blockName)
    {
        // Recursively try to find the block in the themes assigned to $view,
        // then of its parent form, then of the parent form of the parent and so on.
        // When the root form is reached in this recursion, also the default
        // themes are taken into account.

        // Check each theme whether it contains the searched block
        if (isset($this->themes[$cacheKey])) {
            for ($i = count($this->themes[$cacheKey]) - 1; $i >= 0; --$i) {
                if ($this->loadResourceFromTheme($cacheKey, $blockName, $this->themes[$cacheKey][$i])) {
                    return true;
                }
            }
        }

        // Check the default themes once we reach the root form without success
        if (!$view->parent) {
            for ($i = count($this->defaultThemes) - 1; $i >= 0; --$i) {
                if ($this->loadResourceFromTheme($cacheKey, $blockName, $this->defaultThemes[$i])) {
                    return true;
                }
            }
        }

        // If we did not find anything in the themes of the current view, proceed
        // with the themes of the parent view
        if ($view->parent) {
            $parentCacheKey = $view->parent->vars[self::CACHE_KEY_VAR];

            if (!isset($this->resources[$parentCacheKey][$blockName])) {
                $this->loadResourceForBlockName($parentCacheKey, $view->parent, $blockName);
            }

            // If a template exists in the parent themes, cache that template
            // for the current theme as well to speed up further accesses
            if ($this->resources[$parentCacheKey][$blockName]) {
                $this->resources[$cacheKey][$blockName] = $this->resources[$parentCacheKey][$blockName];

                return true;
            }
        }

        // Cache that we didn't find anything to speed up further accesses
        $this->resources[$cacheKey][$blockName] = false;

        return false;
    }

    /**
     * Tries to load the resource for a block from a theme.
     *
     * @param string $cacheKey  The cache key for storing the resource
     * @param string $blockName The name of the block to load a resource for
     * @param mixed  $theme     The theme to load the block from
     *
     * @return bool True if the resource could be loaded, false otherwise
     */
    protected function loadResourceFromTheme($cacheKey, $blockName, $theme)
    {
        $templateName = sprintf('%s/%s.blade.php', $theme, $blockName);
        $template = $this->parser->parse($templateName);
        $storage = $this->loader->load($template);

        if (false === $storage) {
            return false;
        }

        $this->resources[$cacheKey][$blockName] = $storage;
        return true;
    }
}