<?php

namespace App\Importers;

use App\Collection;
use App\Import;
use App\Repositories\IFileRepository;

class NgImporter extends AbstractImporter {

    protected $options = [
        'delimiter' => ',',
        'enclosure' => '"',
        'escape' => '\\',
        'newline' => "\r\n",
        // we use UTF-8 now. no need to convert encoding.
        // 'input_encoding' => 'CP1250',
    ];

    protected $mapping = [
        'Název díla' => 'title',
        'z cyklu' => 'related_work',
        'Technika' => 'technique',
        'Materiál' => 'medium',
        'Ivent. číslo - zobrazované' => 'identifier',
        'Popis' => 'description',
        'OSA 1' => 'date_earliest',
        'OSA 2' => 'date_latest',
        // translations
        'Ang - Název díla (překlad předchozího sloupce)' => 'title:en',
        'Ang - Popis (překlad předchozího sloupce)' => 'description:en',
        'Ang - Technika (překlad předchozího sloupce)' => 'technique:en',
        'Ang - Materiál (překlad předchozího sloupce)' => 'medium:en',
    ];

    protected $defaults = [
        'work_type' => '',
        'topic' => '',
        'relationship_type' => '',
        'place' => '',
        'gallery' => '',
        'description' => '',
        'description:cs' => '',
        'description:en' => '',
        'title' => '',
        'title:cs' => '',
        'title:en' => '',
        'technique' => '',
        'technique:cs' => '',
        'technique:en' => '',
        'medium' => '',
        'medium:cs' => '',
        'medium:en' => '',
        'has_rights' => 0,
    ];

    protected static $cz_gallery_collection_spec = [
        'SGK' => 'Sbírka grafiky a kresby',
        'SMSU' => 'Sbírka moderního a současného umění',
        'SUDS' => 'Sbírka umění 19. století',
        'SSU' => 'Sbírka starého umění',
        'SAA' => 'Sbírka umění Asie a Afriky',
    ];

    protected static $en_gallery_collection_spec = [
        'SGK' => 'Collection of Prints and Drawings',
        'SMSU' => 'Collection of Modern and Contemporary Art',
        'SUDS' => 'Collection of the Art of the 19th Century',
        'SSU' => 'Collection of Old Masters',
        'SAA' => 'Collection of Asian and African Art',
    ];

    protected static $name = 'ng';

    public function __construct(IFileRepository $repository, array $locales) {
        parent::__construct($repository, $locales);

        $this->sanitizers[] = function($value) {
            return empty_to_null($value);
        };
    }

    protected function getItemId(array $record) {
        $key = 'Ivent. číslo - pracovní';
        $id = $record[$key];
        $id = strtr($id, ' ', '_');

        return sprintf('CZE:NG.%s', $id);
    }

    protected function getItemImageFilenameFormat(array $record) {
        $key = 'Ivent. číslo - pracovní';
        $filename = $record[$key];
        $filename = strtr($filename, ' ', '_');

        return $filename;
    }

    protected function createItem(array $record) {
        $item = parent::createItem($record);

        $collection = Collection::whereTranslation('name', $record['Kolekce (budova)'], 'cs')->first();
        if ($collection) {
            $item->collections()->sync([$collection->id]);
        }

        return $item;
    }

    protected function getImageJpgPaths(Import $import, $csv_filename, $image_filename_format) {
        $path = storage_path(sprintf(
            'app/import/%s/%s/%s.{jpg,jpeg,JPG,JPEG}',
            $import->dir_path,
            pathinfo($csv_filename, PATHINFO_FILENAME),
            $image_filename_format
        ));

        return glob($path, GLOB_BRACE);
    }

    protected function getImageJp2Paths(Import $import, $csv_filename, $image_filename_format) {
        $path = sprintf(
            '%s/%s/%s',
            config('importers.iip_base_path'),
            $import->iip_dir_path,
            $image_filename_format
        );

        $main = glob($path . '.jp2');
        $other = glob($path . '--*.jp2');

        return array_merge($main, $other);
    }

    protected function hydrateAuthor(array $record, $locale = 'cs') {
        $keys = [
            'Autor (jméno příjmení, příp. Anonym)',
            'Autor 2',
            'Autor 3',
            'Autor 4',
        ];

        if ($locale == 'en') {
            $keys = [
                'Ang - Autor (překlad předchozího sloupce)',
                'Ang - Autor 2 (překlad předchozího sloupce)',
                'Ang - Autor 3 (překlad předchozího sloupce)',
                'Ang - Autor 4 (překlad předchozího sloupce)',
            ];
        }

        $keys = array_filter($keys, function ($key) use ($record) {
            return $record[$key] !== null;
        });

        $authors = array_filter(
            array_map(function($key) use ($record) {
                return self::bracketAuthor($record[$key]);
            }, $keys)
        );

        return implode('; ', $authors);
    }

    protected function hydrateInscription(array $record, $locale = 'cs') {
        $key1 = 'značeno kde (umístění v díle)';
        $key2 = 'Značeno (jak např. letopočet, signatura, monogram)';

        if ($locale == 'en') {
            $key = 'Ang - Značeno (překlad předchozího sloupce)';
            return $record[$key];
        }

        $inscription = [];
        if ($record[$key1] !== null) {
            $inscription[] = $record[$key1];
        }

        if ($record[$key2] !== null) {
            $inscription[] = $record[$key2];
        }

        return implode(': ', $inscription);
    }

    protected function hydrateMeasurement(array $record, $locale = 'cs') {
        $measurement = [];

        $width = 'šířka';
        $height = 'výška';
        $depth = 'hloubka';
        $units = 'jednotky';

        $units_suffix = $record[$units] !== null ? sprintf(' %s', $record[$units]) : '';
        if ($record[$height] !== null) {
            $measurement[] = sprintf('%s %s%s', \Lang::get('dielo.height', [], $locale), $record[$height], $units_suffix);
        }
        if ($record[$width] !== null) {
            $measurement[] = sprintf('%s %s%s', \Lang::get('dielo.width', [], $locale), $record[$width], $units_suffix);
        }
        if ($record[$depth] !== null) {
            $measurement[] = sprintf('%s %s%s', \Lang::get('dielo.depth', [], $locale), $record[$depth], $units_suffix);
        }

        return implode('; ', $measurement);
    }

    protected function hydrateGalleryCollection(array $record, $locale = 'cs') {
        if ($locale == 'en') {
            return isset(self::$en_gallery_collection_spec[$record['Sbírka']]) ? self::$en_gallery_collection_spec[$record['Sbírka']] : null;
        }
        return isset(self::$cz_gallery_collection_spec[$record['Sbírka']]) ? self::$cz_gallery_collection_spec[$record['Sbírka']] : null;
    }

    protected function hydrateDating(array $record, $locale = 'cs') {
        if ($locale == 'en') {
            return $record['Ang - Datování NEBO Datace'];
        }
        return $record['Datace'] !== null ? $record['Datace'] : $record['Datování (určené)'];
    }

    protected function hydrateHasRights(array $record) {
        return ($record['Práva'] != '5 - WEB + Náhled obrázku');
    }

    protected function hydrateFreeDownload(array $record) {
        return ($record['Práva'] == '7 - WEB + ZOOM obrázku + Stažení');
    }

    /**
     * @param string $author
     * @return string
     */
    protected static function bracketAuthor($author) {
        return preg_replace('/,\s*(.*)/', ' ($1)', $author);
    }
}
