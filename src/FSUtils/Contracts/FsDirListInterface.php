<?php

namespace Tsc\CatStorageSystem\FSUtils\Contracts;


use Tsc\CatStorageSystem\Contracts\DirectoryInterface;
use Tsc\CatStorageSystem\Directory;

interface FsDirListInterface
{
    /**
     * @param DirectoryInterface $directory
     * @return Directory[]
     */
    public function list(DirectoryInterface $directory): array ;
}