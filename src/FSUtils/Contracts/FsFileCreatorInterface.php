<?php

namespace Tsc\CatStorageSystem\FSUtils\Contracts;
use \SplFileInfo;
use Tsc\CatStorageSystem\Contracts\DirectoryInterface;
use Tsc\CatStorageSystem\Contracts\FileInterface;

interface FsFileCreatorInterface
{
    /**
     * @param FileInterface $file
     * @param DirectoryInterface $parent
     * @return SplFileInfo
     */
    public function create(FileInterface $file, DirectoryInterface $parent): SplFileInfo;
}