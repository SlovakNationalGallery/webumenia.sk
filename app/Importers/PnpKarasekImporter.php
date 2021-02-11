<?php

namespace App\Importers;

use App\Import;
use App\ImportRecord;
use App\Repositories\IFileRepository;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Str;

class PnpKarasekImporter extends AbstractImporter
{
    protected $mapping = [
        'author' => 'Autor:',
        'date_earliest' => 'Rok od:',
        'date_latest' => 'Rok do:',
        'identifier' => 'Inventární číslo:',
        'title:sk' => 'Název:',
        'title:cs' => 'Název:',
        'dating:sk' => 'Datace:',
        'dating:cs' => 'Datace:',
        'measurement:sk' => 'Rozměry:',
        'measurement:cs' => 'Rozměry:',
        'inscription:sk' => 'Značení:',
        'inscription:cs' => 'Značení:',
        'description:sk' => 'Popis:',
        'description:cs' => 'Popis:',
    ];

    protected $defaults = [
        'gallery:sk' => 'Památník národního písemnictví, PNP',
        'gallery:cs' => 'Památník národního písemnictví, PNP',
    ];

    protected $workTypeTranslations = [
        'sk' => [
            'malba' => 'maľba',
            'kresba' => 'kresba',
            'grafika' => 'grafika',
            'busta' => 'busta',
            'socha' => 'socha',
            'akvarel' => 'akvarel',
            'kvaš' => 'gvaš',
            'plastika' => 'plastika',
            'pastel' => 'pastel',
        ],
        'en' => [
            'malba' => 'painting',
            'kresba' => 'drawing',
            'grafika' => 'graphics',
            'busta' => 'bust',
            'socha' => 'statue',
            'akvarel' => 'watercolor',
            'kvaš' => 'ferment',
            'plastika' => 'plastic',
            'pastel' => 'pastel',
        ],
    ];

    protected $topicTranslations = [
        'sk' => [
            'krajina' => 'krajina',
            'figurální' => 'figurálny',
            'autoportrét' => 'autoportrét',
            'fauna' => 'fauna',
            'portrét' => 'portrét',
            'architektura' => 'architektura',
            'město' => 'mesto',
            'žánr' => 'žáner',
            'marina' => 'marína',
            'zátiší' => 'zátišie',
            'kostým' => 'kostým',
            'náboženský' => 'náboženský',
            'mytologický' => 'mytologický',
            'alegorický' => 'alegorický',
            'veduta' => 'veduta',
            'divadlo' => 'divadlo',
            'dekorativní' => 'dekorativny',
            'alegorie' => 'alegória',
            'církevní' => 'cirkevný',
            'historický' => 'historický',
            'karikatura' => 'karikatúra',
            'ornamentální' => 'ornamentálny',
        ],
        'en' => [
            'krajina' => 'country',
            'figurální' => 'figural',
            'autoportrét' => 'self-portrait',
            'fauna' => 'fauna',
            'portrét' => 'portrait',
            'architektura' => 'architecture',
            'město' => 'city',
            'žánr' => 'genre',
            'marina' => 'marina',
            'zátiší' => 'still life',
            'kostým' => 'costume',
            'náboženský' => 'religious',
            'mytologický' => 'mythological',
            'alegorický' => 'allegorical',
            'veduta' => 'veduta',
            'divadlo' => 'theater',
            'dekorativní' => 'decorative',
            'alegorie' => 'allegory',
            'církevní' => 'ecclesiastical',
            'historický' => 'historical',
            'karikatura' => 'caricature',
            'ornamentální' => 'ornamental',
        ],
    ];

    protected $mediumTranslations = [
        'sk' => [
            'plátno' => 'plátno',
            'papír' => 'papier',
            'lepenka na dřevě' => 'lepenka na dreve',
            'lepenka' => 'lepenka',
            'olej' => 'olej',
            'plátno na lepence' => 'plátno na lepenke',
            'deska' => 'doska',
            'karton' => 'kartón',
            'sádra patinovaná na keramiku' => 'sádra patinovaná na keramiku',
            'pálená hlína' => 'pálená hlina',
            'plátno na překližce' => 'plátno na preglejke',
            'dřevo' => 'drevo',
            'plá' => 'plá',
            'pergamen' => 'pergamen',
            'papír modrošedý' => 'papier modrošedý',
            'polokarton' => 'polokartón',
            'patinovaná sádra' => 'patinovaná sádra',
            'balicí papír' => 'baliaci papier',
            'bronz' => 'bronz',
            'vápenec' => 'vápenec',
        ],
        'en' => [
            'plátno' => 'canvas',
            'papír' => 'paper',
            'lepenka na dřevě' => 'cardboard on wood',
            'lepenka' => 'cardboard',
            'olej' => 'oil',
            'plátno na lepence' => 'canvas on cardboard',
            'deska' => 'plate',
            'karton' => 'cardboard',
            'sádra patinovaná na keramiku' => 'patinated gypsum plaster',
            'pálená hlína' => 'burnt clay',
            'plátno na překližce' => 'canvas on plywood',
            'dřevo' => 'wood',
            'plá' => 'beach',
            'pergamen' => 'parchment',
            'papír modrošedý' => 'blue-gray paper',
            'polokarton' => 'half cardboard',
            'patinovaná sádra' => 'patinated plaster',
            'balicí papír' => 'wrapping paper',
            'bronz' => 'bronze',
            'vápenec' => 'limestone',
        ],
    ];

    protected $techniqueTranslations = [
        'sk' => [
            'olej' => 'olej',
            'akvarel' => 'akvarel',
            'lepenka' => 'lepenka',
            'tempera' => 'tempera',
            'plastika' => 'plastika',
            'kombinovaná technika' => 'kombinovaná technika',
            'dřevoryt' => 'drevoryt',
            'tónovaná akvatinta s leptem' => 'tónovaná akvatinta s leptom',
            'barevný' => 'farebný',
            'mezzotinta' => 'mezzotinta',
            'dřevořez' => 'drevorez',
            'mědiryt' => 'medirytina',
            'kolorovaný' => 'kolorovaný',
            'rytina' => 'rytina',
            'kresba štětcem' => 'kresba štětcom',
            'tinta' => 'tinta',
            'kresba křídou' => 'kresba kríedou',
            'kresba uhlem' => 'kresba uhľom',
            'kolorováno' => 'kolorované',
            'černá tinta' => 'čierna tinta',
            'perokresba' => 'perokresba',
            'kresba tužkou' => 'kresba ceruzou',
            'křídou' => 'kriedou',
            'lavírováno' => 'lavírované',
            'akvatinta' => 'akvatinta',
            'litografie' => 'litografia',
            'suchá jehla' => 'suchá ihla',
            'černá křída' => 'čierna krieda',
            'heliogravura' => 'heliogravúra',
            'lept' => 'lept',
            'pero' => 'pero',
            'štětec' => 'štětec',
            'grafit' => 'grafit',
            'rudka' => 'rudka',
            'lavírovaná' => 'lavírovaná',
            'kolorovaná tuš' => 'kolorovaný tuš',
            'kvaš' => 'gvaš',
            'barevná křída' => 'farebná krieda',
            'tužka' => 'ceruza',
            'pastel' => 'pastel',
            'tuš' => 'tuš',
            'běloba' => 'beloba',
            'křída' => 'krieda',
            'uhel' => 'uhoľ',
            'bílá křída' => 'biela krieda',
            'linoryt' => 'linoryt',
            'linořez' => 'linorez',
        ],
        'en' => [
            'olej' => 'oil',
            'akvarel' => 'watercolor',
            'lepenka' => 'cardboard',
            'tempera' => 'tempera',
            'plastika' => 'plastic',
            'kombinovaná technika' => 'combined technique',
            'dřevoryt' => 'woodcut',
            'tónovaná akvatinta s leptem' => 'tinted aquatint with etching',
            'barevný' => 'color',
            'mezzotinta' => 'mezzotint',
            'dřevořez' => 'woodcut',
            'mědiryt' => 'copperplate',
            'kolorovaný' => 'colored',
            'rytina' => 'engraving',
            'kresba štětcem' => 'brush drawing',
            'tinta' => 'tinta',
            'kresba křídou' => 'chalk drawing',
            'kresba uhlem' => 'charcoal drawing',
            'kolorováno' => 'colored',
            'černá tinta' => 'black tinta',
            'perokresba' => 'line drawing',
            'kresba tužkou' => 'pencil drawing',
            'křídou' => 'chalk',
            'lavírováno' => 'wash',
            'akvatinta' => 'aquatint',
            'litografie' => 'lithography',
            'suchá jehla' => 'drypoint',
            'černá křída' => 'black chalk',
            'heliogravura' => 'heliogravure',
            'lept' => 'lept',
            'pero' => 'but',
            'štětec' => 'brush',
            'grafit' => 'graphite',
            'rudka' => 'rudka',
            'lavírovaná' => 'wash',
            'kolorovaná tuš' => 'colored ink',
            'kvaš' => 'gouache',
            'barevná křída' => 'colored chalk',
            'tužka' => 'pencil',
            'pastel' => 'pastel',
            'tuš' => 'ink',
            'běloba' => 'white',
            'křída' => 'chalk',
            'uhel' => 'coal',
            'bílá křída' => 'white chalk',
            'linoryt' => 'linocut',
            'linořez' => 'linocut',
        ],
    ];

    protected $counter;

    protected static $name = 'pnp_karasek';

    public function __construct(IFileRepository $repository, Translator $translator)
    {
        parent::__construct($repository, $translator);
        $this->sanitizers[] = function ($value) {
            return empty_to_null($value);
        };
    }

    public function import(Import $import, array $file)
    {
        $this->counter = 0;
        return parent::import($import, $file);
    }

    public function importSingle(array $record, Import $import, ImportRecord $import_record)
    {
        $this->counter++;
        return parent::importSingle($record, $import, $import_record);
    }

    protected function getItemId(array $record)
    {
        return sprintf("CZE:PNP.%s", $this->getSlug($record['Inventární číslo:']));
    }

    protected function getItemImageFilenameFormat(array $record)
    {
        return $this->getSlug($record['Inventární číslo:']);
    }

    protected function getSlug($identifier)
    {
        return strtr($identifier, ' ', '_');
    }

    public function hydrateWorkType(array $record, $locale)
    {
        return $this->translateSingle($record['Výtvarný druh:'], 'workTypeTranslations', $locale);
    }

    public function hydrateTopic(array $record, $locale)
    {
        return $this->translateMultiple($record['Námět:'], 'topicTranslations', $locale);
    }

    public function hydrateMedium(array $record, $locale)
    {
        return $this->translateSingle($record['Materiál:'], 'mediumTranslations', $locale);
    }

    public function hydrateTechnique(array $record, $locale)
    {
        return $this->translateMultiple($record['Technika:'], 'techniqueTranslations', $locale);
    }

    public function hydrateAdditionals(array $record, $locale)
    {
        if ($locale !== 'cs') {
            return null;
        }

        return [
            'set' => $record['Soubor:'],
            'category' => $record['Kategorie:'],
            'author_birth_year' => $record['Datum narození:'],
            'author_death_year' => $record['Datum úmrtí:'],
            'author_alternative_name' => $record['Alternatívni jméno:'],
            'author_role' => $record['Role:'],
            'order' => $this->counter,
            'frontend' => ['karasek.pamatniknarodnihopisemnictvi.cz'],
        ];
    }

    protected function translateSingle($single, $map, $locale)
    {
        if ($single === null) {
            return null;
        }

        $single = Str::lower($single);

        if ($locale === 'cs') {
            return $single;
        }

        return $this->{$map}[$locale][$single];
    }

    protected function translateMultiple($multiple, $map, $locale)
    {
        if ($multiple === null) {
            return null;
        }

        $exploded = explode(',', $multiple);
        $translated = array_map(function ($single) use ($map, $locale) {
            $single = trim($single);
            return $this->translateSingle($single, $map, $locale);
        }, $exploded);
        return implode('; ', $translated);
    }
}