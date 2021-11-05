<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Newsletter\NewsletterFacade as Newsletter;

class NewsletterSignupForm extends Component
{
    public $email;
    public bool $success = false;

    protected $rules = [
        'email' => 'required|email',
    ];

    public function subscribe()
    {
        $this->success = false;
        $this->validate();

        Newsletter::subscribePending(
            $this->email,
        );

        if (!Newsletter::lastActionSucceeded()) {
            $this->addError('subscription', Newsletter::getLastError());
            return;
        };

        $this->success = true;
    }

    public function render()
    {
        return view('livewire.newsletter-signup-form');
    }
}
