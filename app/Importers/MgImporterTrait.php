<?php

namespace App\Importers;

trait MgImporterTrait
{
    protected array $mediumTranslationKeys;
    protected array $techniqueTranslationKeys;
    protected array $workTypeTranslationKeys;
    protected array $topicTranslationKeys;

    protected function init(): void
    {
        $this->filters[] = function (array $record) {
            return $record['Plus2T'] != 'ODPIS';
        };

        $this->sanitizers[] = function ($value) {
            return empty_to_null($value);
        };

        $this->mediumTranslationKeys = array_flip(trans('item.media', locale: 'cs'));
        $this->techniqueTranslationKeys = array_flip(trans('item.techniques', locale: 'cs'));
        $this->workTypeTranslationKeys = array_flip(trans('item.work_types', locale: 'cs'));
        $this->topicTranslationKeys = array_flip(trans('item.topics', locale: 'cs'));
    }

    protected function getItemId(array $record): string
    {
        $id = sprintf('CZE:MG.%s_%s', $record['Rada_S'], (int) $record['PorC_S']);
        if ($record['Lomeni_S'] != '_') {
            $id = sprintf('%s-%s', $id, $record['Lomeni_S']);
        }

        return $id;
    }

    protected function getItemImageFilenameFormat(array $record): string
    {
        $filename = sprintf(
            '%s%s',
            $record['Rada_S'],
            str_pad($record['PorC_S'], 6, '0', STR_PAD_LEFT)
        );
        if ($record['Lomeni_S'] != '_') {
            $filename = sprintf('%s-%s', $filename, $record['Lomeni_S']);
        }

        return sprintf('%s(_.*)?', preg_quote($filename));
    }

    protected function hydrateIdentifier(array $record): string
    {
        $identifier = sprintf('%s %s', $record['Rada_S'], (int) $record['PorC_S']);
        if ($record['Lomeni_S'] != '_') {
            $identifier = sprintf('%s/%s', $identifier, $record['Lomeni_S']);
        }

        return $identifier;
    }
}
