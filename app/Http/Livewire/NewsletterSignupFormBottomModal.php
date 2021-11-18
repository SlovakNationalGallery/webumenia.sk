<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Date;
use Livewire\Component;

class NewsletterSignupFormBottomModal extends Component
{
    public int $openOnScrolledPercent = 0;

    public function onOpen()
    {
        $this->emit('trackNewsletterSignup', 'modalOpen');
    }

    public function onDismissed()
    {
        Cookie::queue(
            'newsletterSignupModalDismissedAt',
             Date::now()->toIso8601String(),
             Date::now()->addDays(30)->diffInMinutes()
        );
        $this->emit('trackNewsletterSignup', 'modalDismissed');
    }

    public function render()
    {
        if (Cookie::has('newsletterSubscribedAt')) return '';
        if (Cookie::has('newsletterSignupModalDismissedAt')) return '';
        return view('livewire.newsletter-signup-form-bottom-modal');
    }
}
