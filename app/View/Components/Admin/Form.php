<?php

namespace App\View\Components\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class Form extends Component
{
    public Model $model;

    public string $url;

    public string $method;

    public bool $files;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        Model $model,
        ?string $url = null,
        ?string $method = null,
        ?bool $files = false
    ) {
        $this->model = $model;
        $this->url = $this->buildUrl($url);
        $this->method = $this->buildMethod($method);
        $this->files = $files;
    }

    private function buildUrl(?string $url): string
    {
        if ($url) {
            return $url;
        }

        // Try to guess url from model class
        $routeName = Str::of(class_basename($this->model))
            ->plural()
            ->kebab();

        if ($this->model->exists) {
            return route("$routeName.update", [$this->model]);
        }

        return route("$routeName.store");
    }

    private function buildMethod(?string $method): string
    {
        if ($method) {
            return $method;
        }

        if ($this->model->exists) {
            return 'patch';
        }

        return 'post';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.form');
    }
}
