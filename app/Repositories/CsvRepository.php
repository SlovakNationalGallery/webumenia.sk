<?php

namespace App\Repositories;

use League\Csv\Reader;

class CsvRepository implements IFileRepository
{
    public function getAll($resource, array $options = []): \Iterator
    {
        $reader = $this->createReader($resource, $options);
        $reader->setHeaderOffset(0);
        return $reader->getRecords();
    }

    public function getFiltered($resource, array $filters, array $options = []): \Iterator
    {
        $all = $this->getAll($resource, $options);
        return $this->filter($all, $filters);
    }

    protected function createReader($resource, array $options = []): Reader
    {
        $reader = Reader::createFromStream($resource);

        if (isset($options['delimiter'])) {
            $reader->setDelimiter($options['delimiter']);
        }

        if (isset($options['enclosure'])) {
            $reader->setEnclosure($options['enclosure']);
        }

        if (isset($options['escape'])) {
            $reader->setEscape($options['escape']);
        }

        if (isset($options['input_encoding'])) {
            if (!$reader->supportsStreamFilterOnRead()) {
                throw new \LogicException('Stream filter is not active');
            }

            $conversionFilter = $this->getConversionFilter($options['input_encoding']);
            $reader->addStreamFilter($conversionFilter);
        }

        return $reader;
    }

    protected function getConversionFilter(string $input_encoding): string
    {
        return sprintf('convert.iconv.%s/UTF-8', $input_encoding);
    }

    protected function filter(\Iterator $records, array $filters): \Iterator
    {
        return new \CallbackFilterIterator($records, function ($current, $key) use ($filters) {
            foreach ($filters as $filter) {
                if (!$filter($current, $key)) {
                    return false;
                }
            }

            return true;
        });
    }
}
