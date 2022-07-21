<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = ['url', 'label', 'type'];

    public function linkable()
    {
        return $this->morphTo();
    }
}
