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

    public function save(array $options = []) {
        if ($this->order === null && $this->item) {
            $max = $this->item->images()->max('order');
            $this->order = $max !== null ? $max + 1 : 0;
        }
        return parent::save($options);
    }

    public function isZoomable() {
        return $this->iipimg_url !== null;
    }
}
