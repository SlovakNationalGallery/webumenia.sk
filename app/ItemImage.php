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

    public function save(array $options = []) {
        if ($this->order === null) {
            $max = $this->item->images()->max('order');
            $this->order = $max !== null ? $max + 1 : 0;
        }
        return parent::save($options);
    }

    public function isZoomable() {
        return $this->iipimg_url !== null;
    }
}
