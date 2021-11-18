<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NewsletterSignupFormBottomModal extends Component
{
    public int $openOnScrolledPercent;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($openOnScrolledPercent = 0)
    {
        $this->openOnScrolledPercent = $openOnScrolledPercent;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.newsletter-signup-form-bottom-modal');
    }
}
