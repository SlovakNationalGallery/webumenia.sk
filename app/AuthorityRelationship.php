<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorityRelationship extends Model
{
    use HasFactory;
    protected $table = 'authority_relationships';
    public $timestamps = false;
    protected $fillable = ['authority_id', 'related_authority_id', 'name', 'type'];
}
