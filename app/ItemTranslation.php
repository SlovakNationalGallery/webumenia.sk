<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'title', 
        'description', 
        'work_type', 
        'work_level', 
        'topic', 
        'subject', 
        'measurement', 
        'dating', 
        'medium', 
        'technique', 
        'inscription', 
        'place', 
        'state_edition', 
        'gallery', 
        'relationship_type', 
        'related_work'
    ];
}
