<?php

namespace App\View\Components;

use App\Article;
use App\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class Teaser extends Component
{
    private string $type;
    private int $id;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id, $type)
    {
        $this->type = $type;
        $this->id = $id;
    }

    public static function buildFromMarkup($markup): ?Teaser
    {
        $matches = [];
        preg_match('/id=(.+?) type=(article|collection)/', $markup, $matches);

        if (empty($matches)) {
            return null;
        }

        [$_, $id, $type] = $matches;
        return new Teaser($id, $type);
    }

    public function render()
    {
        if ($this->type === 'article') {
            $article = Article::published()->find($this->id);
            if (empty($article)) {
                return '';
            }
            return Blade::render('<x-article_teaser :article="$article" />', [
                'article' => $article,
            ]);
        }
        if ($this->type === 'collection') {
            $collection = Collection::published()->find($this->id);
            if (empty($collection)) {
                return '';
            }
            return Blade::render('<x-collection_teaser :collection="$collection" />', [
                'collection' => $collection,
            ]);
        }
        return '';
    }
}
