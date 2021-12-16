<?php

namespace App\Harvest\Mappers;

use App\Harvest\Mappers\AbstractMapper;
use App\Item;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class GmuhkItemMapper extends AbstractMapper
{
    protected $modelClass = Item::class;

    protected $translator;

    public function __construct(Translator $translator)
    {
        parent::__construct();
        $this->translator = $translator;
    }

    public function mapId(array $row)
    {
        $matches = $this->parseIdentifier($row['id'][0]);
        return sprintf(
            'CZE:%s.%s_%d%s',
            $matches['gallery'],
            $matches['work_type'],
            $matches['number'],
            isset($matches['part']) ? '_' . (int)$matches['part'] : ''
        );
    }

    public function mapIdentifier(array $row)
    {
        return $row['identifier'][0] ?? '';
    }

    public function mapAuthor(array $row)
    {
        return $row['author'][0];
    }

    public function mapTitle(array $row)
    {
        return $row['title'][0];
    }

    public function mapDating(array $row)
    {
        return $row['dating'][0] ?? null;
    }

    public function mapTechnique(array $row)
    {
        return $row['technique'][0] ?? null;
    }

    public function mapMedium(array $row)
    {
        return $row['medium'][0] ?? null;
    }
    
    public function mapMeasurement(array $row, $locale)
    {
        $replacements = $this->translator->get('item.measurement_replacements', [], $locale);
        return strtr($row['measurement'][0], $replacements);
    }
    
    public function mapGallery(array $row)
    {
        return $row['gallery'][0];
    }

    public function mapDateEarliest(array $row)
    {
        if (!isset($row['date_earliest'][0])) {
            return null;
        }

        return Date::create($row['date_earliest'][0])->format('Y');
    }

    public function mapDateLatest(array $row)
    {
        if (!isset($row['date_latest'][0])) {
            return null;
        }

        return Date::create($row['date_latest'][0])->format('Y');
    }

    public function mapWorkType(array $row, $locale)
    {
        $matches = $this->parseIdentifier($row['id'][0]);
        return $this->translator->get(sprintf('gmuhk.work_types.%s', $matches['work_type']), [], $locale);
    }

    protected function parseIdentifier($id)
    {
        preg_match('/^.*:(?<gallery>.*)~publikacePredmetu~(?<work_type>[A-Z]+)(?<number>\d+)(\/(?<part>\d+))?$/i', $id, $matches);
        return $matches;
    }
}