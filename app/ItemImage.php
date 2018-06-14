<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class ItemImage extends Model
{
    protected $fillable = [
        'title',
        'img_url',
        'iipimg_url',
        'item_id',
        'order'
    ];

    public function item() {
        return $this->belongsTo(Item::class);
    }

    public static function create(array $attributes = []) {
    	if (array_key_exists('item_id', $attributes)) {
    		$item_id = $attributes['item_id'];
    		$attributes['item_id'] = $item_id;

    		$item_id_query = static::where('item_id', $item_id);
    		$order = $item_id_query->count() == 0 ? 0 : $item_id_query->max('order') + 1;
    		$attributes['order'] = $order;
    	}
    	$model = parent::create($attributes);
    	return $model;
    }
}
