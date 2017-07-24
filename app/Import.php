<?php



namespace App;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{

    const STATUS_QUEUED      = 'queued';
    const STATUS_IN_PROGRESS = 'in progress';
    const STATUS_COMPLETED   = 'completed';
    const STATUS_ERROR       = 'error';
    const STATUS_DELETED     = 'deleted';
    const STATUS_KILLED      = 'killed';

    public static $cz_work_types = [
        'Ar' => 'architektúra',
        'F' =>  'fotografia',
        'G' =>  'grafika',
        'U' =>  'úžitkové umenie',
        'M' =>  'iné médiá',
        'K' =>  'kresba',
        'O' =>  'maliarstvo',
        'P' =>  'sochárstvo',
    ];

    public static $cz_work_types_spec = [
        'Ar' => 'architektura',
        'Bi' => 'bibliofilie a staré tisky',
        'Dř' => 'dřevo, nábytek a design',
        'Fo' => 'fotografie',
        'Gr' => 'grafika',
        'Gu' => 'grafický design',
        'Ji' => 'jiné',
        'Ke' => 'keramika',
        'Ko' => 'kovy',
        'Kr' => 'kresba',
        'Ob' => 'obrazy',
        'Sk' => 'sklo',
        'So' => 'sochy',
        'Šp' => 'šperky',
        'Te' => 'textil',
    ];

    public static $cz_measurement_replacements = [
        'a' => 'výška hlavní části',
        'a.' => 'výška hlavní části',
        'b' => 'výška vedlejší části',
        'b.' => 'výška vedlejší části',
        'čas' => 'čas',
        'd' => 'délka',
        'd.' => 'délka',
        'h' => 'hloubka/tloušťka',
        'h.' => 'hloubka/tloušťka',
        'hmot' => 'hmotnost',
        'hmot.' => 'hmotnost',
        'hr' => 'hloubka s rámem',
        'jiný' => 'jiný nespecifikovaný',
        'p' => 'průměr/ráže',
        'p.' => 'průměr/ráže',
        'r.' => 'ráže',
        'ryz' => 'ryzost',
        's' => 'šířka',
        's.' => 'šířka',
        'sd.' => 'šířka grafické desky',
        'sp' => 'šířka s paspartou',
        'sp.' => 'šířka s paspartou',
        'sr' => 'šířka s rámem',
        'v' => 'celková výška/délka',
        'v.' => 'celková výška/délka',
        'vd.' => 'výška grafické desky',
        'vp' => 'výška s paspartou',
        'vp.' => 'výška s paspartou',
        'vr' => 'výška s rámem',
        ';' => ';',
        '=' => ' ',
        'cm' => ' cm',
    ];

    public static $rules = array(
        'name' => 'required',
    );

    public function records()
    {
        return $this->hasMany('App\ImportRecord');
    }

    public function lastRecord()
    {
      return $this->records->last();
    }

    public function setDirPath($value)
    {
        $this->attributes['dir_path'] = $value ?: null;
    }

    public static function getWorkType($rada, $skupina)
    {
        // $work_type = (isSet(self::$cz_work_types[$rada])) ? self::$cz_work_types[$rada] : 'maliarstvo';
        // if (isSet(self::$cz_work_types_spec[$skupina])) {
        //     if ($skupina == 'Gu') {
        //         $work_type = 'grafika';
        //     }
        //     $work_type .= ', ' . self::$cz_work_types_spec[$skupina];
        // }

        $work_type = (isSet(self::$cz_work_types_spec[$skupina])) ? self::$cz_work_types_spec[$skupina] : 'nespecifikované';
        return $work_type;
    }

    public static function getMeasurement($sluz)
    {
        return (!empty($sluz) && $sluz!= '=') ? strtr($sluz, self::$cz_measurement_replacements)  : '';
    }

}
