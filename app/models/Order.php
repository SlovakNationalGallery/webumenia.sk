<?php

class Order extends Eloquent {

    public static $rules = array(
        'name' => 'required',
        'email' => 'email|required',
        'format' => 'required',
        'delivery_point' => 'required',
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