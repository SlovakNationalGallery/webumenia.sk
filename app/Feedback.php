<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{

    protected $fillable = array(
        'email',
        'text',
    );

    public function item() {
        return $this->belongsTo(Item::class);
    }

}
