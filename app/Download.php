<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    public static $rules = [
        'pids' => 'required',
        'type' => 'required',
        'email' => 'email',
        // 'terms_and_conditions' => 'required',
    ];

    protected $fillable = [
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

    public function items()
    {
        return $this->belongsToMany(\App\Item::class, 'download_item', 'download_id', 'item_id');
    }

    public function hasItem($item) {
        return $this->items->contains($item);
    }



}
