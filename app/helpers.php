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