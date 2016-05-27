<?php



namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    public static $rules = array(
        'name' => 'required',
        'email' => 'email|required',
        'format' => 'required',
        );

    public static $availablePurposeKinds = array(
        'Súkromný' => 'súkromný',
        'Komerčný' => 'komerčný',
        'Výskumný' => 'výskumný',
        'Edukačný' => 'edukačný',
        'Výstava' => 'výstava'
    );


    public function items()
    {
        return $this->belongsToMany('Item', 'order_item', 'order_id', 'item_id');
    }
}
