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
        return 'zo zbierok <strong><a href="/informacie">'. formatNum(7) .'</a></strong> slovenských galérií';
    }

    public static function fromAuthors()
    {
        return 'od <strong><a href="/autori">'. formatNum(Authority::amount()) .'</a></strong> autorov';
    }

    public static function inHightRes()
    {
        return 'z toho <strong><a href="/katalog?has_iip=1">'. formatNum(Item::amount(['has_iip'=>true])) .'</a></strong> vo vysokom rozlíšení';
    }

    public static function areFree()
    {
        return 'z toho <strong><a href="/katalog?is_free=1">'. formatNum(Item::amount(['is_free'=>true])) .'</a></strong> autorsky voľných';
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
