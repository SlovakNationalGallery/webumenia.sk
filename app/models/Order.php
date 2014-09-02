<?php

class Order extends Eloquent {

    public static $rules = array(
        'name' => 'required',
        'email' => 'email|required',
        'format' => 'required',
        );


	public function items()
    {
        return $this->belongsToMany('Item', 'order_item', 'order_id', 'item_id');
    }

}