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

    public static $inv_rady = [
        'A'      => 'malba',
        'Ar'     => 'architektura',
        'B'      => 'kresba',
        'BF'     => 'bibliofilie a knižní vazba',
        'C'      => 'grafika',
        'E'      => 'sochy',
        'ES'     => 'výtv.um. (sb. Eduarda Sýkory)',
        'F'      => 'fotografie',
        'G'      => 'grafika',
        'GD'     => 'grafický design',
        'GDPM'   => 'pomocný materiál',
        'GDR'    => 'grafický design Riedl',
        'GDR_PM' => 'GDR pomocný materiál',
        'K'      => 'kresby',
        'M'      => 'obrazy, kresby (Gomperzova s)',
        'MM'     => 'výtv. um. (sb.Matice moravské)',
        'N'      => 'pro nevidomé',
        'O'      => 'obrazy',
        'P'      => 'plastiky',
        'SD'     => 'soukr.dep.:obr,sochy,gr,kres.',
        'SDK'    => 'stát.dep.kreseb',
        'SDR'    => 'Státní deponát rytina',
        'ST'     => 'staré tisky',
        'STD'    => 'stát.dep.:obrazy, sochy, kresb',
        'U'      => 'užité umění',
        'Z'      => 'převody:obr.,sochy, kresby',
    ];

    public static $skupiny = [
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

    public static function getWorkType($skupina, $podskupina = '', $predmet = '')
    {
        $work_types = [];

        // if (isSet(self::$inv_rady[$rada])) {
        //     $work_types[] = self::$inv_rady[$rada];
        // }

        if (isSet(self::$skupiny[$skupina]) &&
            array_search(self::$skupiny[$skupina], $work_types) === false) {
            $work_types[] = self::$skupiny[$skupina];
        }

        if (!empty($podskupina) && array_search($podskupina, $work_types) === false) {
            $work_types[] = $podskupina;
        }

        if (!empty($predmet) && array_search($predmet, $work_types) === false) {
            $work_types[] = $predmet;
        }

        // fallback
        if (empty($work_types)) {
            $work_types[] = 'nespecifikované';
        }

        return implode(', ', $work_types);
    }

    public static function getMeasurement($sluz)
    {
        return (!empty($sluz) && $sluz!= '=') ? strtr($sluz, self::$cz_measurement_replacements)  : '';
    }

}
