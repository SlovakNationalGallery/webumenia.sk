<?php



namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    public static $rules = [
        'name' => 'required',
        'email' => 'email|required',
        'format' => 'required',
        'terms_and_conditions' => 'required',
        ];

    public function items()
    {
        return $this->belongsToMany(\App\Item::class, 'order_item', 'order_id', 'item_id');
    }
}
