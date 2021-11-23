<?php

namespace App\Observers;

use App\Redirect;
use Illuminate\Support\Facades\Cache;

class RedirectObserver
{
    public function created(Redirect $redirect)
    {
        $this->forgetCache();
    }

    public function updated(Redirect $redirect)
    {
        $this->forgetCache();
    }

    public function deleted(Redirect $redirect)
    {
        $this->forgetCache();
    }

    public function restored(Redirect $redirect)
    {
        $this->forgetCache();
    }

    private function forgetCache()
    {
        Cache::forget('redirects');
    }
}
