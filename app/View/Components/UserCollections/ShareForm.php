<?php

namespace App\View\Components\UserCollections;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class ShareForm extends Component
{
    public string $action;
    public string $method;
    public Collection $items;
    public $collection;
    public bool $disabled;
    public bool $creating;

    public function __construct($items, $collection = null, $action = '', $method = 'POST', $disabled = false, $creating = false)
    {
        $this->action = $action; 
        $this->method = $method; 
        $this->items = $items; 
        $this->disabled = $disabled;
        $this->creating = $creating; 
        $this->collection = $collection; 
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.user-collections.share-form');
    }
}
