<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Date;
use Livewire\Component;
use Spatie\Newsletter\NewsletterFacade as Newsletter;

class NewsletterSignupForm extends Component
{
    public $email;
    public string $variant = 'inline';
    public bool $success = false;

    protected $rules = [
        'email' => 'required|email',
    ];

    public function subscribe()
    {
        $this->validate();

        Newsletter::subscribePending(
            $this->email,
        );

        if (!Newsletter::lastActionSucceeded()) {
            $this->addError('subscription', Newsletter::getLastError());
            return;
        };

        $this->success = true;
        Cookie::queue(
            'newsletterSubscribedAt',
             Date::now()->toIso8601String(),
             Date::now()->addYears(10)->diffInMinutes()
        );
        $this->emit('trackNewsletterSignup', 'signupSuccessful', $this->variant);
    }

    public function render()
    {
        return view('livewire.newsletter-signup-form');
    }
}
