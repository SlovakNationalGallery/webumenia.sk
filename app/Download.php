<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    public static $rules = [
        'item_id' => 'required',
        'type' => 'required',
        'email' => 'email',
        // 'terms_and_conditions' => 'required',
    ];

    protected $guarded = ['terms_and_conditions'];

}
