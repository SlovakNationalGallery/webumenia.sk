<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{

    protected $fillable = array(
        'url',
        'label',
        'locale',
    );

    public static $rules = array(
        'url' => 'url|required',
        'locale' => 'in:sk,en|required',
    );

    public function linkable()
    {
        return $this->morphTo();
    }

    public static function parse($url)
    {
        $url_parts = parse_url($url);
        return $url_parts['host'];
    }
}
