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

    protected $fillable = [
        'item_id',
        'type',
        'company',
        'address',
        'country',
        'contact_person',
        'email',
        'phone',
        'purpose',
        'note',
        'publication_name',
        'publication_author',
        'publication_year',
        'publication_print_run',
        'ip',
    ];

}
