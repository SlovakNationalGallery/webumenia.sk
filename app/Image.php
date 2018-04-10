<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class Image extends Model
{
    protected $fillable = [
        'title',
        'img_url',
        'iipimg_url',
    ];

    public function item() {
        return $this->belongsTo(Item::class);
    }
}
