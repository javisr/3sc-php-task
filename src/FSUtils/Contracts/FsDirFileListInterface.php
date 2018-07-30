<?php

namespace Tsc\CatStorageSystem\FSUtils\Contracts;


use Tsc\CatStorageSystem\Contracts\DirectoryInterface;
use Tsc\CatStorageSystem\File;

interface FsDirFileListInterface
{
    /**
     * @param DirectoryInterface $directory
     * @return File[]
     */
    public function list(DirectoryInterface $directory): array ;
}