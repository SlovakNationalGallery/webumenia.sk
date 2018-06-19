<?php


namespace App\Importers;


use App\Collection;
use App\Image;
use App\Import;
use App\ImportRecord;
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
        'Ang - Popis (překlad předchozího sloupce)' => 'description:en',
    ];

    protected $defaults = [
        'work_type' => '',
        'topic' => '',
        'relationship_type' => '',
        'place' => '',
        'gallery' => '',
        'description' => '',
        'title' => '',
        'technique' => '',
        'medium' => '',
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

    public function __construct(IFileRepository $repository) {
        parent::__construct($repository);

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

    protected function getItemImageFilename(array $record) {
        $key = 'Ivent. číslo - pracovní';
        $filename = $record[$key];
        $filename = strtr($filename, ' ', '_');

        return $filename;
    }

    protected function getItemIipImageUrl($csv_filename, $image_filename) {
        return sprintf('/NG/jp2/%s', $image_filename);
    }

    protected function importSingle(array $record, Import $import, ImportRecord $import_record) {
        $item = parent::importSingle($record, $import, $import_record);

        $image_filename = $this->getItemImageFilename($record);
        $image_paths = $this->getItemIipImagePaths($import, $image_filename);
        $count = $item->images->count();
        foreach ($image_paths as $path) {
            $iipimg_url = $this->getItemIipImageUrl(null, basename($path));
            if ($item->images->where('iipimg_url', $iipimg_url)->count()) {
                continue;
            }

            $item->images()->save($this->createImage($iipimg_url, $count++));
        }

        return $item;
    }

    protected function createItem(array $record) {
        $item = parent::createItem($record);

        $collection = Collection::where('name', $record['Kolekce (budova)'])->first();
        if ($collection) {
            $item->collections()->sync([$collection->id]);
        }

        return $item;
    }

    /**
     * @param string $iipimg_url
     * @param int $order
     * @return Image
     */
    protected function createImage($iipimg_url, $order) {
        $image = new Image();
        $image->iipimg_url = $iipimg_url;
        $image->order = $order;

        return $image;
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
            return $record[$key];;
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
     * @param Import $import
     * @param string $image_filename
     * @return string
     */
    protected function getItemIipImagePaths(Import $import, $image_filename) {
        $main = sprintf('%s/%s.jp2', $import->iip_dir_path, $image_filename);
        $other = sprintf('%s/%s--*.jp2', $import->iip_dir_path, $image_filename);

        return array_merge(glob($main), glob($other));
    }

    /**
     * @param string $author
     * @return string
     */
    protected static function bracketAuthor($author) {
        return preg_replace('/,\s*(.*)/', ' ($1)', $author);
    }
}
