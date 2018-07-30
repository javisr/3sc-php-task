<?php

namespace Tsc\CatStorageSystem\FSUtils\Contracts;


use Tsc\CatStorageSystem\Contracts\DirectoryInterface;

interface FsDirFileCounterInterface
{
    /**
     * @param DirectoryInterface $directory
     * @return int
     */
    public function calculate(DirectoryInterface $directory): int;
}