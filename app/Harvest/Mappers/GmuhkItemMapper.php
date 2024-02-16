<?php

namespace App\Harvest\Mappers;

use App\Item;

class GmuhkItemMapper extends AbstractMapper
{
    use MuseionTrait;

    protected $modelClass = Item::class;

    public function __construct()
    {
        parent::__construct();
        $this->mediumTranslationKeys = array_flip(trans('item.media', locale: 'cs'));
        $this->techniqueTranslationKeys = array_flip(trans('item.techniques', locale: 'cs'));
    }

    protected array $workTypeTranslationKeys = [
        'F' => 'fotografia',
        'G' => 'grafika',
        'K' => 'kresba',
        'O' => 'maliarstvo',
        'P' => 'sochárstvo/plastika',
        'VA' => 'iné médiá/video',
        'GVP' => 'sochárstvo/skulptúra',
    ];

    public function mapId(array $row): string
    {
        $matches = $this->parseIdentifier($row['id'][0]);

        return sprintf(
            'CZE:%s.%s%d%s',
            $matches['gallery'],
            isset($matches['work_type']) ? $matches['work_type'] . '_' : '',
            $matches['number'],
            isset($matches['part']) ? '_' . (int) $matches['part'] : ''
        );
    }

    public function mapMeasurement(array $row, string $locale): ?string
    {
        if (!isset($row['measurement'][0])) {
            return null;
        }

        $replacements = trans('item.measurement_replacements', locale: $locale);
        return strtr($row['measurement'][0], $replacements);
    }

    public function mapWorkType(array $row, $locale)
    {
        $matches = $this->parseIdentifier($row['id'][0]);
        $key = $this->workTypeTranslationKeys[$matches['work_type']];
        return trans("item.work_types.$key", locale: $locale);
    }


    protected function parseIdentifier($id): array
    {
        preg_match('/^.*:(?<gallery>.*)~publikacePredmetu~(?<work_type>[A-Z]+)(?<number>\d+)(\/(?<part>\d+))?$/i', $id, $matches);
        return $matches;
    }
}
