<?php

namespace App;

class Subtitle
{

    protected static $availavle_methods = array(
            'fromGalleries',
            'fromAuthors',
            'inHightRes',
            'areFree',
            // 'fromWorkType'
        );

    public static function fromGalleries()
    {
        return trans('intro.from_galleries_start').' <strong><a href="/informacie">'. formatNum(7) .'</a></strong> '.trans('intro.from_galleries_end');
    }

    public static function fromAuthors()
    {
        return trans('intro.from_authors_start').' <strong><a href="/autori">'. formatNum(Authority::amount()) .'</a></strong> '.trans('intro.from_authors_end');
    }

    public static function inHightRes()
    {
        return trans('intro.in_high_res_start').' <strong><a href="/katalog?has_iip=1">'. formatNum(Item::amount(['has_iip'=>true])) .'</a></strong> '.trans('intro.in_high_res_end');
    }

    public static function areFree()
    {
        return trans('intro.are_free_start').' <strong><a href="/katalog?is_free=1">'. formatNum(Item::amount(['is_free'=>true])) .'</a></strong> '.trans('intro.are_free_end');
    }

    // public static function fromWorkType()
    // {
    // 	return 'z toho <strong>'. 10 .'</strong> grafík/kresieb/malieb...';
    // }

    public static function random()
    {
        $method = self::$availavle_methods[array_rand(self::$availavle_methods)];
        return self::$method();
    }
}
