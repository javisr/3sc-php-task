<?php

namespace Tsc\CatStorageSystem\FSUtils;

use SplFileInfo;
use Tsc\CatStorageSystem\Contracts\DirectoryInterface;
use Tsc\CatStorageSystem\Contracts\FileInterface;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsFileCreatorInterface;

class FileCreator implements FsFileCreatorInterface
{

    /**
     * @param FileInterface $file
     * @param DirectoryInterface $parent
     * @return SplFileInfo
     * @throws \Exception
     */
    public function create(FileInterface $file, DirectoryInterface $parent): SplFileInfo
    {
        $filePath = $parent->getPath() . '/' . $parent->getName() . '/' . $file->getName();

        if ( ! touch($filePath)) {
            throw new \Exception('There was an error creating the file');
        }

        return new SplFileInfo($filePath);
    }
}