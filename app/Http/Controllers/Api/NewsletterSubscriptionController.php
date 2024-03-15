<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use DrewM\MailChimp\MailChimp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class NewsletterSubscriptionController extends Controller
{
    public function store(Request $request, MailChimp $mailChimp)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $listId = config('mailchimp.lists.webumenia-newsletter.id');

        $mailChimp->post("lists/{$listId}/members", [
            'email_address' => $request->email,
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
            abort(500, $mailChimp->getLastError());
        }

        return response('', 201)->cookie(
            'newsletterSubscribedAt',
            Date::now()->toIso8601String(),
            Date::now()
                ->addYears(10)
                ->diffInMinutes()
        );
    }
}
