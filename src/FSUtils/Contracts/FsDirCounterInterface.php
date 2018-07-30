<?php

namespace Tsc\CatStorageSystem\FSUtils\Contracts;


use Tsc\CatStorageSystem\Contracts\DirectoryInterface;

interface FsDirCounterInterface
{
    /**
     * @param DirectoryInterface $directory
     * @return int
     */
    public function calculate(DirectoryInterface $directory): int;
}