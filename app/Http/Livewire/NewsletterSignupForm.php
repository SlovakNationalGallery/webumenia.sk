<?php

namespace App\Http\Livewire;

use DrewM\MailChimp\MailChimp;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Date;
use Livewire\Component;

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
        $mailChimp = new MailChimp(config('mailchimp.apiKey'));
        $listId = config('mailchimp.lists.webumenia-newsletter.id');

        $mailChimp->post("lists/{$listId}/members", [
            'email_address' => $this->email,
            'status' => 'pending',
            'email_type' => 'html',
            'marketing_permissions' => [
                [
                    'marketing_permission_id' => config(
                        'mailchimp.lists.webumenia-newsletter.marketing_permissions.default'
                    ),
                    'enabled' => true,
                ],
            ],
        ]);

        if (!$mailChimp->success()) {
            $this->addError('subscription', $mailChimp->getLastError());
            return;
        }

        $this->success = true;
        Cookie::queue(
            'newsletterSubscribedAt',
            Date::now()->toIso8601String(),
            Date::now()
                ->addYears(10)
                ->diffInMinutes()
        );
        $this->emit('trackNewsletterSignup', 'signupSuccessful', $this->variant);
    }

    public function render()
    {
        return view('livewire.newsletter-signup-form');
    }
}
