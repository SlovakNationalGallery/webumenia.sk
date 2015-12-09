<?php 

	/**
	* Same as java String.hashcode()
	*/
	function hashcode($s) {
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

	    if ($value & 0x80000000)
	        $value = -((~$value & 0xFFFFFFFF) + 1);

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
		if (empty($date)) return false;
		$parts = explode('.', $date);
		$date = implode('-', array_reverse($parts));
		for ($i=count($parts); $i < 3; $i++) { 
			$date = $date.'-01';
		}
		
		return Carbon::parse($date);
	}

	function str_to_alphanumeric( $string , $replace_with = '')
    {
        return trim(preg_replace("/[^[:alnum:][:space:]]/ui", $replace_with, $string));
    }

    function br2nl($html) {
    	return preg_replace('#<br\s*/?>#i', "\n", $html);
    }

    /**
     * uzavrie retazec do daneho paru znakov (), '', "" atd...
     * ak je prazdny, vrati prazdny retazec
     */
    function str_enclose($str, $enclose_with='()')
    {
    	return ($str) ? $enclose_with[0] . $str . $enclose_with[1] : '';
    }

    function getNextVal(&$array, $curr_val)
    {
        $next = false;
        reset($array);

        do
        {
            $tmp_val = current($array);
            $res = next($array);
        } while ( ($tmp_val != $curr_val) && $res );

        if( $res )
        {
            $next = current($array);
        }

        return $next;
    }

    function getPrevVal(&$array, $curr_val)
    {
        end($array);
        $prev = false;

        do
        {
            $tmp_val = current($array);
            $res = prev($array);
        } while ( ($tmp_val != $curr_val) && $res );

        if( $res )
        {
            $prev = current($array);
        }

        return $prev;
    }

    function asset_timed($path, $secure=null){
        $file = public_path($path);
        if(file_exists($file)){
            return asset($path, $secure) . '?' . filemtime($file);
        }else{
            throw new \Exception('The file "'.$path.'" cannot be found in the public folder');
        }
    }

    function getTitleWithFilters($model, $input, $end_with = '') {
        $title_parts = array();
        $separator = ' &bull; ';
        foreach ($model::$filterable as $label=>$filter) {
            if (!empty($input[$filter])){
                if ($input[$filter] == '1') {
                    $title_parts[] = $label;
                } else {
                    if ($filter == 'author') $input[$filter] = preg_replace('/^([^,]*),\s*(.*)$/', '$2 $1', $input[$filter]); //otoc meno a priezvisko
                    $title_parts[] = $label . ': ' . $input[$filter];   
                }
            }
        }
        // fazety, ktore niesu typu "term" treba zadefinovat osobitne, pretoze niesu vo $filterable
        if (isSet($input['year-range'])) {
            $range = explode(',', $input['year-range']);
            if($range[0] > $model::sliderMin()) $title_parts[] = 'po' . ': ' . $range[0];
            if($range[1] < $model::sliderMax()) $title_parts[] = 'do' . ': ' . $range[1];
        }
        if (!empty($input['first-letter'])) {
            $title_parts[] = 'začína sa na' . ': "' . $input['first-letter'] . '"';
        }
        if (empty($title_parts)) $end_with = '';
        return implode($separator, $title_parts) . $end_with;
    }

    function addMicrodata($value, $itemprop) {
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
