<?php

namespace App\Forms;

use Illuminate\View\Engines\EngineInterface;
use Illuminate\View\Factory;
use Illuminate\View\ViewFinderInterface;
use Symfony\Component\Form\AbstractRendererEngine;
use Symfony\Component\Form\FormView;

class BladeRendererEngine extends AbstractRendererEngine
{
    /** @var EngineInterface */
    protected $engine;

    /** @var Factory */
    protected $viewFactory;

    public function __construct(
        EngineInterface $engine,
        Factory $viewFactory,
        array $defaultThemes = []
    ) {
        parent::__construct($defaultThemes);
        $this->engine = $engine;
        $this->viewFactory = $viewFactory;
    }

    public function renderBlock(FormView $view, $resource, $blockName, array $variables = array())
    {
        $cacheKey = $view->vars[self::CACHE_KEY_VAR];
        $view = $this->viewFactory->make($this->resources[$cacheKey][$blockName], $variables);
        return $view->render();
    }

    /**
     * Loads the cache with the resource for a given block name.
     *
     * This implementation eagerly loads all blocks of the themes assigned to the given view
     * and all of its ancestors views. This is necessary, because Twig receives the
     * list of blocks later. At that point, all blocks must already be loaded, for the
     * case that the function "block()" is used in the Twig template.
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
        // The caller guarantees that $this->resources[$cacheKey][$block] is
        // not set, but it doesn't have to check whether $this->resources[$cacheKey]
        // is set. If $this->resources[$cacheKey] is set, all themes for this
        // $cacheKey are already loaded (due to the eager population, see doc comment).
        if (isset($this->resources[$cacheKey])) {
            // As said in the previous, the caller guarantees that
            // $this->resources[$cacheKey][$block] is not set. Since the themes are
            // already loaded, it can only be a non-existing block.
            $this->resources[$cacheKey][$blockName] = false;

            return false;
        }

        // Recursively try to find the block in the themes assigned to $view,
        // then of its parent view, then of the parent view of the parent and so on.
        // When the root view is reached in this recursion, also the default
        // themes are taken into account.

        // Check each theme whether it contains the searched block
        if (isset($this->themes[$cacheKey])) {
            for ($i = count($this->themes[$cacheKey]) - 1; $i >= 0; --$i) {
                $this->loadResourcesFromTheme($cacheKey, $this->themes[$cacheKey][$i]);
                // CONTINUE LOADING (see doc comment)
            }
        }

        // Check the default themes once we reach the root view without success
        if (!$view->parent) {
            for ($i = count($this->defaultThemes) - 1; $i >= 0; --$i) {
                $this->loadResourcesFromTheme($cacheKey, $this->defaultThemes[$i]);
                // CONTINUE LOADING (see doc comment)
            }
        }

        // Proceed with the themes of the parent view
        if ($view->parent) {
            $parentCacheKey = $view->parent->vars[self::CACHE_KEY_VAR];

            if (!isset($this->resources[$parentCacheKey])) {
                $this->loadResourceForBlockName($parentCacheKey, $view->parent, $blockName);
            }

            // EAGER CACHE POPULATION (see doc comment)
            foreach ($this->resources[$parentCacheKey] as $nestedBlockName => $resource) {
                if (!isset($this->resources[$cacheKey][$nestedBlockName])) {
                    $this->resources[$cacheKey][$nestedBlockName] = $resource;
                }
            }
        }

        // Even though we loaded the themes, it can happen that none of them
        // contains the searched block
        if (!isset($this->resources[$cacheKey][$blockName])) {
            // Cache that we didn't find anything to speed up further accesses
            $this->resources[$cacheKey][$blockName] = false;
        }

        return false !== $this->resources[$cacheKey][$blockName];
    }

    /**
     * Loads the resources for all blocks in a theme.
     *
     * @param string $cacheKey The cache key for storing the resource
     * @param mixed  $theme    The theme to load the block from. This parameter
     *                         is passed by reference, because it might be necessary
     *                         to initialize the theme first. Any changes made to
     *                         this variable will be kept and be available upon
     *                         further calls to this method using the same theme.
     */
    protected function loadResourcesFromTheme($cacheKey, $theme)
    {
        $hints = $this->viewFactory->getFinder()->getHints();
        $template_namespace = config('form.template_namespace');
        $theme_dirs = $hints[$template_namespace];

        $theme_locator = function($theme) use ($theme_dirs) {
            foreach ($theme_dirs as $theme_dir) {
                if (basename($theme_dir) == $theme && is_dir($theme_dir)) {
                    return $theme_dir;
                }
            }
        };

        // The while loop takes care of template inheritance.
        // Add blocks from all templates in the inheritance tree, but avoid
        // overriding blocks already set.
        while ($theme_dir = $theme_locator($theme)) {
            foreach (app('files')->files($theme_dir) as $file) {
                foreach ($this->viewFactory->getExtensions() as $extension => $engine) {
                    $extension = ".$extension";
                    if (basename($file, $extension) !== basename($file)) {
                        $block = basename($file, $extension);
                        break;
                    }
                }

                if (!isset($block)) {
                    continue; // invalid (not template) file
                }

                if (!isset($this->resources[$cacheKey][$block])) {
                    // The resource given back is the key to the bucket that
                    // contains this block.
                    $this->resources[$cacheKey][$block] = "$template_namespace::$theme.$block";
                }
            }
            $theme = basename(dirname($theme_dir));
        }
    }
}