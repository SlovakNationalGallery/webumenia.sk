<?php

namespace App\Repositories;


interface IFileRepository {

    /**
     * @param string $file
     * @return \Iterator
     */
    public function getAll($file);

    /**
     * @param string $file
     * @param callable[] $filters
     * @return \Iterator
     */
    public function getFiltered($file, array $filters);
}