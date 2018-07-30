<?php

namespace Tsc\CatStorageSystem\FSUtils;

use SplFileInfo;
use Tsc\CatStorageSystem\Contracts\DirectoryInterface;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsCreatorInterface;

class DirCreator implements FsCreatorInterface
{

    /**
     * @param DirectoryInterface $directory
     * @param DirectoryInterface $parent
     * @return SplFileInfo
     * @throws \Exception
     */
    public function create($directory, $parent = null): SplFileInfo
    {
        if (null == $parent) {
            $dirPath = $directory->getPath() . '/' . $directory->getName();
        } else {
            $dirPath =  $parent->getPath() . '/' . $parent->getName() . '/' . $directory->getName();
        }

        if ( ! mkdir($dirPath, 0777, true)) {
            throw new \Exception('There was an error creating the directory');
        }

        return new SplFileInfo($dirPath);
    }
}