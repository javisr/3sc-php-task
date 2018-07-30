<?php

namespace Tsc\CatStorageSystem\FSUtils;

use Tsc\CatStorageSystem\Contracts\DirectoryInterface;
use Tsc\CatStorageSystem\Factories\FileFactory;
use Tsc\CatStorageSystem\File;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsDirFileListInterface;

class DirFileList implements FsDirFileListInterface
{
    /**
     * @param DirectoryInterface $directory
     * @return File[]
     */
    public function list(DirectoryInterface $directory): array
    {
        $files = [];

        $directoryIterator = new \DirectoryIterator($directory->getPath() . '/' . $directory->getName());

        foreach ($directoryIterator as $item) {
            if ( ! $item->isDir() && ! $item->isDot()) {
                $files[] = FileFactory::create()
                    ->setParentDirectory($directory)
                    ->setName($item->getFilename())
                    ->setSize($item->getSize())
                    ->setCreatedTime((new \DateTime())->setTimestamp($item->getCTime()));
            }
        }

        return $files;
    }
}