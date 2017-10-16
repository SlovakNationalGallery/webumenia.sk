<?php

    /**
    * Same as java String.hashcode()
    */
function hashcode($s)
{
    $len = strLen($s);
    $sum = 0;
    for ($i = 0; $i < $len; $i++) {
        $char = ord($s[$i]);
        $sum = (($sum<<5)-$sum)+$char;
        $sum = $sum & 0xffffffff; // Convert to 32bit integer
    }

    return $sum;
}

    /**
    * Same as java String.valueOf()
    */
function intval32bits($value)
{
    $value = ($value & 0xFFFFFFFF);

    if ($value & 0x80000000) {
        $value = -((~$value & 0xFFFFFFFF) + 1);
    }

    return $value;
}

function url_to($to, $params)
{
    $queryString = http_build_query($params);
    return URL::to($to).'?'.$queryString;
}

function add_brackets($str)
{
    if (empty($str)) {
        return '';
    } else {
        return ' (' . $str . ')';
    }
}

    /**
     * akceptuje datum narodenia/umrtia vo formate [dd.[mm.[yyyy]]]
     */
function cedvuDatetime($date)
{
    if (empty($date)) {
        return false;
    }
    $parts = explode('.', $date);
    $date = implode('-', array_reverse($parts));
    for ($i=count($parts); $i < 3; $i++) {
        $date = $date.'-01';
    }
        
    return Carbon::parse($date);
}

function str_to_alphanumeric($string, $replace_with = '')
{
    return trim(preg_replace("/[^[:alnum:][:space:]-]/ui", $replace_with, $string));
}

function br2nl($html)
{
    return preg_replace('#<br\s*/?>#i', "\n", $html);
}

    /**
     * uzavrie retazec do daneho paru znakov (), '', "" atd...
     * ak je prazdny, vrati prazdny retazec
     */
function str_enclose($str, $enclose_with = '()')
{
    return ($str) ? $enclose_with[0] . $str . $enclose_with[1] : '';
}

function getNextVal(&$array, $curr_val)
{
    $next = false;
    reset($array);

    do {
        $tmp_val = current($array);
        $res = next($array);
    } while (($tmp_val != $curr_val) && $res);

    if ($res) {
        $next = current($array);
    }

    return $next;
}

function getPrevVal(&$array, $curr_val)
{
    end($array);
    $prev = false;

    do {
        $tmp_val = current($array);
        $res = prev($array);
    } while (($tmp_val != $curr_val) && $res);

    if ($res) {
        $prev = current($array);
    }

    return $prev;
}

function asset_timed($path, $secure = null)
{
    $file = public_path($path);
    if (file_exists($file)) {
        return asset($path, $secure) . '?' . filemtime($file);
    } else {
        throw new \Exception('The file "'.$path.'" cannot be found in the public folder');
    }
}

function getTitleWithFilters($model, $input, $end_with = '')
{
    $title_parts = array();
    $separator = ' &bull; ';
    foreach ($model::$filterable as $label => $filter) {
        if (!empty($input[$filter])) {
            if ($input[$filter] == '1') {
                $title_parts[] = $label;
            } else {
                if ($filter == 'author') {
                    $input[$filter] = preg_replace('/^([^,]*),\s*(.*)$/', '$2 $1', $input[$filter]); //otoc meno a priezvisko
                }                $title_parts[] = $label . ': ' . $input[$filter];
            }
        }
    }
    // fazety, ktore niesu typu "term" treba zadefinovat osobitne, pretoze niesu vo $filterable
    if (isset($input['year-range'])) {
        $range = explode(',', $input['year-range']);
        if ($range[0] > $model::sliderMin()) {
            $title_parts[] = 'po' . ': ' . $range[0];
        }
        if ($range[1] < $model::sliderMax()) {
            $title_parts[] = 'do' . ': ' . $range[1];
        }
    }
    if (!empty($input['first-letter'])) {
        $title_parts[] = 'začína sa na' . ': "' . $input['first-letter'] . '"';
    }
    if (empty($title_parts)) {
        $end_with = '';
    }
    return implode($separator, $title_parts) . $end_with;
}

function addMicrodata($value, $itemprop)
{
    return '<span itemprop="'.$itemprop.'">'.$value.'</span>';
}

function getCanonicalUrl()
{
    $unwanted_params = [
        'tag',
        'sort_by',
        'year-range',
        'has_image',
        'has_iip',
        'is_free',
        'first_letter',
    ];

    $url = Request::url();
    $params = array_filter(Input::except($unwanted_params), 'strlen'); //vyhodi nechcene a prazdne parametre

    if (!empty($params)) {
        $params = array_slice($params, 0, 1); //necha iba prvy parameter v poli
        if (Input::has('page')) {
            $params['page'] = Input::get('page');
        }
        $url .=  '?' . http_build_query($params);
    }
    return $url;
}

function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('', 'k', 'M', 'G', 'T');

    return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
}

function formatNum($num, $decimals = 0)
{
    return number_format($num, $decimals, ',', ' ');
}

function utrans($str)
{
    return mb_ucfirst(trans($str));
}

function mb_ucfirst($str) {
    $str = mb_strtoupper(mb_substr($str, 0, 1)) . mb_substr($str, 1);
    return $str;
}

function isValidURL($url)
{
    $headers = @get_headers($url);
    if(strpos($headers[0],'200')===false)return false;
    return true;
}

function w1250_to_utf8($text) {
    // map based on:
    // http://konfiguracja.c0.pl/iso02vscp1250en.html
    // http://konfiguracja.c0.pl/webpl/index_en.html#examp
    // http://www.htmlentities.com/html/entities/
    $map = array(
        chr(0x8A) => chr(0xA9),
        chr(0x8C) => chr(0xA6),
        chr(0x8D) => chr(0xAB),
        chr(0x8E) => chr(0xAE),
        chr(0x8F) => chr(0xAC),
        chr(0x9C) => chr(0xB6),
        chr(0x9D) => chr(0xBB),
        chr(0xA1) => chr(0xB7),
        chr(0xA5) => chr(0xA1),
        chr(0xBC) => chr(0xA5),
        chr(0x9F) => chr(0xBC),
        chr(0xB9) => chr(0xB1),
        chr(0x9A) => chr(0xB9),
        chr(0xBE) => chr(0xB5),
        chr(0x9E) => chr(0xBE),
        chr(0x80) => '&euro;',
        chr(0x82) => '&sbquo;',
        chr(0x84) => '&bdquo;',
        chr(0x85) => '&hellip;',
        chr(0x86) => '&dagger;',
        chr(0x87) => '&Dagger;',
        chr(0x89) => '&permil;',
        chr(0x8B) => '&lsaquo;',
        chr(0x91) => '&lsquo;',
        chr(0x92) => '&rsquo;',
        chr(0x93) => '&ldquo;',
        chr(0x94) => '&rdquo;',
        chr(0x95) => '&bull;',
        chr(0x96) => '&ndash;',
        chr(0x97) => '&mdash;',
        chr(0x99) => '&trade;',
        chr(0x9B) => '&rsquo;',
        chr(0xA6) => '&brvbar;',
        chr(0xA9) => '&copy;',
        chr(0xAB) => '&laquo;',
        chr(0xAE) => '&reg;',
        chr(0xB1) => '&plusmn;',
        chr(0xB5) => '&micro;',
        chr(0xB6) => '&para;',
        chr(0xB7) => '&middot;',
        chr(0xBB) => '&raquo;',
    );
    return html_entity_decode(mb_convert_encoding(strtr($text, $map), 'UTF-8', 'ISO-8859-2'), ENT_QUOTES, 'UTF-8');
}

function remove_accents($string) {
    if ( !preg_match('/[\x80-\xff]/', $string) )
        return $string;

    $chars = array(
    // Decompositions for Latin-1 Supplement
    chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
    chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
    chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
    chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
    chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
    chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
    chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
    chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
    chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
    chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
    chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
    chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
    chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
    chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
    chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
    chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
    chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
    chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
    chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
    chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
    chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
    chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
    chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
    chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
    chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
    chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
    chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
    chr(195).chr(191) => 'y',
    // Decompositions for Latin Extended-A
    chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
    chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
    chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
    chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
    chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
    chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
    chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
    chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
    chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
    chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
    chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
    chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
    chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
    chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
    chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
    chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
    chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
    chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
    chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
    chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
    chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
    chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
    chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
    chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
    chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
    chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
    chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
    chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
    chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
    chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
    chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
    chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
    chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
    chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
    chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
    chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
    chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
    chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
    chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
    chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
    chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
    chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
    chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
    chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
    chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
    chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
    chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
    chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
    chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
    chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
    chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
    chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
    chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
    chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
    chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
    chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
    chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
    chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
    chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
    chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
    chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
    chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
    chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
    chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
    );

    $string = strtr($string, $chars);

    return $string;
}

function empty_to_null($value) {
    return $value === "" ? NULL : $value;
}

function convertEmptyStringsToNull($array) {
    $array = array_map(function ($e) {
        return $e ?: null;

    }, $array);

    return $array;
}
