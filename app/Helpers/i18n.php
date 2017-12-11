<?php

/**
* Get an associative array with localeCodes as keys and translated URLs of current page as value
*/
function getLocalizedURLArray()
{
    $localesOrdered = LaravelLocalization::getLocalesOrder();;
    $localizedURLs = array();
    foreach ($localesOrdered as $localeCode => $properties) {
        $localizedURLs[$localeCode] = LaravelLocalization::getLocalizedURL($localeCode, null, [], true);
    }
    return $localizedURLs;
}