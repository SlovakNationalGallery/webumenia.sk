<?php

class Order extends Eloquent {

    public static $rules = array(
        'organization' => 'required',
        'contactPerson' => 'required',
        'email' => 'email|required',
        'kindOfPurpose' => 'required',
        'purpose' => 'required',
        'medium' => 'required',
        'numOfCopies' => 'numeric',
        );


	public function items()
    {
        return $this->belongsToMany('Item', 'order_item', 'order_id', 'item_id');
    }

}