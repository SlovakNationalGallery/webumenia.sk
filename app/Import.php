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
        'Dř' => 'dřevo',
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
        'v=' => 'výška ',
        's=' => 'šírka ',
        'h=' => 'dĺžka ',
        ';' => ';',
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
