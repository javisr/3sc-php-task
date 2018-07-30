<?php

namespace Tsc\CatStorageSystem\FSUtils;

use SplFileInfo;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsCreatorInterface;

class DirCreator implements FsCreatorInterface
{

    /**
     * @param string $dirPath
     * @return SplFileInfo
     * @throws \Exception
     */
    public function create(string $dirPath): SplFileInfo
    {
        if ( ! mkdir($dirPath, 0777, true)) {
            throw new \Exception('There was an error creating the directory');
        }

        return new SplFileInfo($dirPath);
    }
}