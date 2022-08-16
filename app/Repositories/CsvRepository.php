<?php

namespace App\Repositories;

use League\Csv\Reader;

class CsvRepository implements IFileRepository
{
    public function getAll($file, array $options = [])
    {
        $reader = $this->createReader($file, $options);
        $reader->setHeaderOffset(0);
        return $reader->getRecords();
    }

    public function getFiltered($file, array $filters, array $options = [])
    {
        $all = $this->getAll($file, $options);
        return $this->filter($all, $filters);
    }

    /**
     * @param string $file
     * @return Reader
     */
    protected function createReader($file, array $options = [])
    {
        $reader = Reader::createFromPath($file, 'r');

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

    /**
     * @param string $input_encoding
     * @return string
     */
    protected function getConversionFilter($input_encoding)
    {
        return sprintf('convert.iconv.%s/UTF-8', $input_encoding);
    }

    /**
     * @param \Iterator $records
     * @return \Iterator
     */
    protected function filter(\Iterator $records, array $filters)
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
