<?php

namespace Tsc\CatStorageSystem\FSUtils;

use Tsc\CatStorageSystem\Contracts\DirectoryInterface;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsDirCounterInterface;

class DirCounter implements FsDirCounterInterface
{

    /**
     * @param DirectoryInterface $directory
     * @return int
     */
    public function calculate(DirectoryInterface $directory): int
    {
        $fileCount = 0;

        $directoryIterator = new \DirectoryIterator($directory->getPath() . '/' . $directory->getName());

        foreach ($directoryIterator as $item) {
            if ($item->isDir() && ! $item->isDot()) {
                $fileCount++;
            }
        }
        return $fileCount;

    }
}