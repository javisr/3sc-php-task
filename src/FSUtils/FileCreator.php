<?php

namespace Tsc\CatStorageSystem\FSUtils;

use SplFileInfo;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsCreatorInterface;

class FileCreator implements FsCreatorInterface
{

    /**
     * @param string $filePath
     * @return SplFileInfo
     * @throws \Exception
     */
    public function create(string $filePath): SplFileInfo
    {
        if ( ! touch($filePath)) {
            throw new \Exception('There was an error creating the file');
        }

        return new SplFileInfo($filePath);
    }
}