<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NewsletterSignupForm extends Component
{
    public $email;
    public bool $success = false;

    protected $rules = [
        'email' => 'required|email',
    ];

    public function subscribe()
    {
        $this->success = !$this->success;
    }

    public function render()
    {
        return view('livewire.newsletter-signup-form');
    }
}
