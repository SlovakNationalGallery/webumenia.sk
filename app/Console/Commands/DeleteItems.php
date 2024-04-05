<?php

namespace App\Console\Commands;

use App\Item;
use App\Repositories\CsvRepository;
use Illuminate\Console\Command;

class DeleteItems extends Command
{
    protected $signature = 'items:delete {file}';

    protected $csvRepository;

    public function __construct(CsvRepository $csvRepository) {
        parent::__construct();
        $this->csvRepository = $csvRepository;
    }

    public function fire()
    {
        $file = $this->argument('file');

        $records = $this->csvRepository->getAll($file, [
            'delimiter' => ';',
            'enclosure' => '"',
            'escape' => '\\',
            'newline' => "\r\n",
            'input_encoding' => 'CP1250',
        ]);

        $ids = collect($records)
            ->map(function ($record) {
                return $this->getItemId($record);
            });

        Item::destroy($ids->toArray());
    }

    protected function getItemId(array $record)
    {
        $id = sprintf('CZE:MG.%s_%s', $record['Rada_S'], (int)$record['PorC_S']);
        if ($record['Lomeni_S'] != '_') {
            $id = sprintf('%s-%s', $id, $record['Lomeni_S']);
        }

        return $id;
    }
}