<?php

return [
    /*
     * The API key of a MailChimp account. You can find yours at
     * https://us10.admin.mailchimp.com/account/api-key-popup/.
     */
    'apiKey' => env('MAILCHIMP_APIKEY'),

    /*
     * Here you can define properties of the lists.
     */
    'lists' => [
        /*
         * This key is used to identify this list. It can be used
         * as the listName parameter provided in the various methods.
         *
         * You can set it to any string you want and you can add
         * as many lists as you want.
         */
        'webumenia-newsletter' => [
            /*
             * A MailChimp list id. Check the MailChimp docs if you don't know
             * how to get this value:
             * http://kb.mailchimp.com/lists/managing-subscribers/find-your-list-id.
             */
            'id' => env('NEWSLETTER_LIST_ID', '1d7d3f54f9'),

            /*
             * The GDPR marketing permissions of this audience.
             */
            'marketing_permissions' => [
                'default' => '1e03249552', // i.e. "Súhlasím"
            ],
        ],
    ],
];
