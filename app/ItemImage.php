<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;


class ItemImage extends Model
{
    const IIP_FULL_URL_PREFIX = '/fcgi-bin/iipsrv.fcgi?DeepZoom=';
    const IIP_FULL_URL_SUFFIX = '.dzi';

    protected $fillable = [
        'title',
        'iipimg_url',
        'item_id',
        'order',
    ];

    public function getIipimgUrl() {
        return $this->iipimg_url;
    }

    public function item() {
        return $this->belongsTo(Item::class);
    }

    public function getFullIIPImgURL()
    {
        return self::IIP_FULL_URL_PREFIX.$this->iipimg_url.self::IIP_FULL_URL_SUFFIX;
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

    public static function loadValidatorMetadata(ClassMetadata $metadata) {
        // $metadata->addGetterMethodConstraint('iipimg_url', 'getIipimgUrl', new NotBlank());
    }
}
