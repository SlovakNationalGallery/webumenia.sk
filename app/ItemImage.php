<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;


class ItemImage extends Model
{
    protected $fillable = [
        'iipimg_url',
        'item_id',
    ];

    public function item() {
        return $this->belongsTo(Item::class);
    }

    public function getDeepZoomUrl() {
        return sprintf(
            '%s/zoom/?path=%s.dzi',
            config('app.iip_public'),
            urlencode($this->iipimg_url)
        );
    }

    public function getPreviewUrl($maxSize = 800) {
        return sprintf(
            '%s/preview/?path=%s&size=%d',
            config('app.iip_public'),
            urlencode($this->iipimg_url),
            $maxSize
        );
    }

    public function getIipimgUrl() {
        return $this->iipimg_url;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata) {
         $metadata->addGetterMethodConstraint('iipimg_url', 'getIipimgUrl', new NotBlank());
    }
}
