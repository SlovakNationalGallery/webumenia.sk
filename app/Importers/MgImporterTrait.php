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

    protected function hydrateExhibition(array $record): ?string
    {
        $exhibitions = [
            '#^UPM/211/.*#' => 'LIGHT DEPO',
            '#^UPM/210/.*#' => 'BLACK DEPO',
            '#^UPM/110/.*#' => 'Jeskyně: Panorama designu',
            '#^UPM/203/D/.*#' => 'Design 2000+',
            '#^UPM/204/.*#' => 'Fashion 2000+',
            '#^UPM/203/POSTMODERNA$#' => 'Postmoderna',
        ];

        foreach ($exhibitions as $pattern => $exhibition) {
            if (preg_match($pattern, $record['AktLokace'])) {
                return $exhibition;
            }
        }

        return null;
    }

    protected function hydrateBox(array $record): ?string
    {
        $replacements = [
            '#^UPM/211/B(\d+)/.*#' => 'BOX $1',
            '#^UPM/210/([A-Z])/B(\d+)/.*#' => 'BOX $1$2',
            '#^UPM/203/D/(.*?)/.*#' => '$1',
        ];

        foreach ($replacements as $pattern => $replacement) {
            if (preg_match($pattern, $record['AktLokace'])) {
                return preg_replace($pattern, $replacement, $record['AktLokace']);
            }
        }

        return null;
    }
}
