<?php

namespace App\View\Components;

use Illuminate\Support\Arr;
use Illuminate\View\Component;

class NewsletterSignupFormBottomModal extends Component
{
    public int $openOnPercentScrolled;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($openOnPercentScrolled = 0)
    {
        $this->openOnPercentScrolled = $openOnPercentScrolled;
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
