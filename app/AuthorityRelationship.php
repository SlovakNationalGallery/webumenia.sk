<?php



namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthorityRelationship extends Model
{
    protected $table = 'authority_relationships';
    public $timestamps = false;
    protected $fillable = array(
            'authority_id',
            'related_authority_id',
            'name',
            'type',
        );
}
