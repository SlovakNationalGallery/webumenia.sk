<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class ItemImage extends Model
{
    const IIP_DEEPZOOM_URL_PREFIX = '/fcgi-bin/iipsrv.fcgi?DeepZoom=';
    const IIP_DEEPZOOM_URL_SUFFIX = '.dzi';
    const IIP_IIIF_URL_PREFIX = '/fcgi-bin/iipsrv.fcgi?IIIF=';

    protected $fillable = [
        'title',
        'img_url',
        'iipimg_url',
        'item_id',
        'order'
    ];

    public function getFullIIPImgURL()
    {
        return self::IIP_DEEPZOOM_URL_PREFIX.$this->iipimg_url.self::IIP_DEEPZOOM_URL_SUFFIX;
    }

    public function getFullIIIFImgURL($region = 'full', $size = '!800,800', $rotation = 0, $quality = 'default')
    {
        return self::IIP_IIIF_URL_PREFIX.$this->iipimg_url.'/'.$region.'/'.$size.'/'.$rotation.'/'.$quality.'.jpg';
    }

    public function item() {
        return $this->belongsTo(Item::class);
    }

    public static function create(array $attributes = []) {
        if (array_key_exists('item_id', $attributes) &&
            !array_key_exists('order', $attributes)) {
            $item_id = $attributes['item_id'];
            $item_id_query = static::where('item_id', $item_id);
            $max = $item_id_query->max('order');
            $order = $max !== null ? $max + 1 : 0;
            $attributes['order'] = $order;
        }

        return parent::create($attributes);
    }

    public function isZoomable() {
        return $this->iipimg_url !== null;
    }
}
