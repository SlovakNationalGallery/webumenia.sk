<?php

namespace App\Mail;

use App\SharedUserCollection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SharedUserCollectionCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $collection;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SharedUserCollection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(trans('user-collection.shared.email.created_subject'))
            ->markdown('emails.shared-user-collection-created', [
                'collection' => $this->collection,
                'shareUrl' => route('frontend.shared-user-collections.show', $this->collection),
                'editUrl' => route('frontend.shared-user-collections.edit', ['collection' => $this->collection, 'token' => $this->collection->update_token])
            ]);
    }
}
