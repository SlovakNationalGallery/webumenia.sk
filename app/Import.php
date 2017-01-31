<?php



namespace App;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{

    public static $rules = array(
        'name' => 'required',
    );

    public function records()
    {
        return $this->hasMany('App\ImportRecord');
    }

}
