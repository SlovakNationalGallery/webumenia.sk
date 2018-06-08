<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class ItemImage extends Model
{
    const IIP_FULL_URL_PREFIX = '/fcgi-bin/iipsrv.fcgi?DeepZoom=';
    const IIP_FULL_URL_SUFFIX = '.dzi';

    protected $fillable = [
        'title',
        'img_url',
        'iipimg_url',
    ];

    public function item() {
        return $this->belongsTo(Item::class);
    }

    public function getFullIIPImgURL()
    {
        return self::IIP_FULL_URL_PREFIX . $this->iipimg_url . self::IIP_FULL_URL_SUFFIX;
    }

    public function isZoomable() {
        return $this->iipimg_url !== null;
    }
}
