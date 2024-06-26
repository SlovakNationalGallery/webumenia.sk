<?php

namespace App\Harvest\Mappers;

use App\Item;

class GmuhkItemMapper extends AbstractMapper
{
    use MuseionTrait;

    protected $modelClass = Item::class;

    protected array $workTypeTranslationKeys = [
        'publikacePredmetu:GMUHK:151:F' => 'fotografia',
        'publikacePredmetu:GMUHK:151:F:Fo' => 'fotografia',
        'publikacePredmetu:GMUHK:151:G:Gr' => 'grafika',
        'publikacePredmetu:GMUHK:151:K:Kr' => 'kresba',
        'publikacePredmetu:GMUHK:151:O:Ob' => 'maliarstvo',
        'publikacePredmetu:GMUHK:151:P' => 'sochárstvo/plastika',
        'publikacePredmetu:GMUHK:151:P:In' => 'sochárstvo/plastika',
        'publikacePredmetu:GMUHK:151:P:So' => 'sochárstvo/plastika',
        'publikacePredmetu:GMUHK:151:VA' => 'iné médiá/video',
        'publikacePredmetu:GMUHK:VA:VA' => 'iné médiá/video',
        'publikacePredmetu:GVP:2535:GVP:So' => 'sochárstvo/skulptúra',
        'publikacePredmetu:SKT:2021:SKT:Fo' => 'fotografia',
        'publikacePredmetu:SKT:2021:SKT:Gr' => 'grafika',
        'publikacePredmetu:SKT:2021:SKT:Kr' => 'kresba',
        'publikacePredmetu:SKT:2021:SKT:Ob' => 'maliarstvo',
        'publikacePredmetu:SKT:2021:SKT:So' => 'sochárstvo',
    ];

    protected array $fallbackWorkTypeTranslationKeys = [
        'F' => 'fotografia',
        'G' => 'grafika',
        'K' => 'kresba',
        'O' => 'maliarstvo',
        'P' => 'sochárstvo/plastika',
        'VA' => 'iné médiá/video',
        'GVP' => 'sochárstvo/skulptúra',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->mediumTranslationKeys = array_flip(trans('item.media', locale: 'cs'));
        $this->techniqueTranslationKeys = array_flip(trans('item.techniques', locale: 'cs'));
    }

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
        $parsedId = $this->parseIdentifier($row['id'][0]);
        $key = $this->workTypeTranslationKeys[$row['work_type'][0]]
            ?? $this->fallbackWorkTypeTranslationKeys[$parsedId['work_type']];

        return trans("item.work_types.$key", locale: $locale);
    }

    public function mapCredit(array $row, $locale): ?string
    {
        if (str($row['identifier'][0])->startsWith('SKT')) {
            return [
                'sk' => 'Zbierka Karla Tutscha',
                'cs' => 'Sbírka Karla Tutsche',
                'en' => 'Karel Tutsch Collection',
            ][$locale];
        }

        return null;
    }

    protected function parseIdentifier($id): array
    {
        preg_match('/^.*:(?<gallery>.*)~publikacePredmetu~(?<work_type>[A-Z]+)(?<number>\d+)(\/(?<part>\d+))?$/i', $id, $matches);
        return $matches;
    }
}
