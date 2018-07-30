<?php

namespace Tsc\CatStorageSystem\FSUtils;

use Tsc\CatStorageSystem\Contracts\DirectoryInterface;
use Tsc\CatStorageSystem\Directory;
use Tsc\CatStorageSystem\Factories\DirectoryFactory;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsDirListInterface;

class DirList implements FsDirListInterface
{
    /**
     * @param DirectoryInterface $directory
     * @return Directory[]
     */
    public function list(DirectoryInterface $directory): array
    {
        $dirs = [];

        $directoryIterator = new \DirectoryIterator($directory->getPath() . '/' . $directory->getName());

        foreach ($directoryIterator as $item) {
            if ($item->isDir() && ! $item->isDot()) {
                $dirs[] = DirectoryFactory::create()
                    ->setName($item->getFilename())
                    ->setPath($item->getPath())
                    ->setCreatedTime((new \DateTime())->setTimestamp($item->getCTime()));
            }
        }
        return $dirs;
    }
}