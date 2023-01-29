<?php

namespace App\View\Components;

use App\Article;
use App\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;
use Illuminate\Support\Str;

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

    public static function buildFromMarkup($match): Teaser
    {
        $id = (int)$match[1];
        $type = Str::of($match[2]);


        if (empty($id) || empty($type)) {
            return '';
        }

        return new Teaser($id, $type);
    }

    public function render()
    {
        if ($this->type === 'article') {
            $article = Article::published()->find($this->id);
            return Blade::render('<x-article_teaser :article="$article" />', [
                'article' => $article,
            ]);
        } elseif ($this->type === 'collection') {
            $collection = Collection::published()->find($this->id);
            return Blade::render('<x-collection_teaser :collection="$collection" />', [
                'collection' => $collection,
            ]);
        } else {
            return '';
        }
    }
}
