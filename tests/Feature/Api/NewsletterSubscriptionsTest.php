<?php

namespace Tests\Feature\Api;

use DrewM\MailChimp\MailChimp;
use Mockery\MockInterface;
use Tests\TestCase;

class NewsletterSubscriptionsTest extends TestCase
{
    public function test_create()
    {
        $this->mock(MailChimp::class, function (MockInterface $mock) {
            $mock->shouldReceive('post')->once();
            $mock->shouldReceive('success')->andReturn(true);
        });

        $this->post(route('api.newsletter-subscriptions.store'), [
            'email' => 'aa@bb.com',
        ])
            ->assertStatus(201)
            ->assertCookie('newsletterSubscribedAt');
    }
    public function test_dismissal()
    {
        $this->post(route('api.newsletter-subscriptions.dismiss'))
            ->assertSuccessful()
            ->assertCookie('newsletterSignupModalDismissedAt');
    }
}
