<?php

namespace App\Harvest\Mappers;

use App\Item;

class MudbItemMapper extends AbstractMapper
{
    use MuseionTrait;

    protected $modelClass = Item::class;

    public function __construct()
    {
        parent::__construct();
        $this->mediumTranslationKeys = array_flip(trans('item.media', locale: 'cs'));
        $this->techniqueTranslationKeys = array_flip(trans('item.techniques', locale: 'cs'));
        $this->mediumCorrectionMap = [
            'fotografie barevný' => 'fotografie barevná',
            'fotografie černobílý' => 'fotografie černobílá',
            'kresba černobílý' => 'kresba černobílá',
            'litografie černobílý' => 'litografie černobílá',
            'ofset autorský černobílá' => 'ofset autorský černobílý',
            'serigrafie barevný' => 'serigrafie barevná',
            'typografie černobílý' => 'typografie černobílá',
            'typografie barevný' => 'typografie barevná',
        ];
    }

    protected array $workTypeTranslationKeys = [
        'Ar' => 'architektúra',
        'D' => 'dizajn',
        'F' => 'fotografia',
        'G' => 'grafika',
        'Gd' => 'grafický dizajn',
        'K' => 'kresba',
        'M' => 'multimédiá',
        'O' => 'maliarstvo',
        'P' => 'sochárstvo/plastika',
    ];

    public function mapId(array $row): string
    {
        return sprintf('CZE:MUDB.%s', $row['identifier'][0]);
    }

    public function mapMeasurement(array $row, string $locale): ?string
    {
        if (!in_array($locale, ['sk', 'cs']) || !isset($row['measurement'][0])) {
            return null;
        }

        return strtr($row['measurement'][0], ['=' => ' ']);
    }

    public function mapWorkType(array $row, string $locale): string
    {
        $workTypeId = str($row['work_type'][0])
            ->explode(':')
            ->last();

        $key = $this->workTypeTranslationKeys[$workTypeId];
        return trans("item.work_types.$key", locale: $locale);
    }

    protected function parseIdentifier($id): array
    {
        preg_match(
            '/^.*:(?<gallery>.*)~publikacePredmetu~(?<work_type>[A-Z]+)?(?<number>\d+)(\/(?<part>\w+))?$/i',
            $id,
            $matches
        );
        return $matches;
    }
}
