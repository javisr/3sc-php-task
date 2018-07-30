<?php

namespace Tsc\CatStorageSystem\FSUtils;

use SplFileInfo;
use Tsc\CatStorageSystem\Contracts\DirectoryInterface;
use Tsc\CatStorageSystem\Contracts\FileInterface;
use Tsc\CatStorageSystem\Factories\DirectoryFactory;
use Tsc\CatStorageSystem\Factories\FileFactory;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsDriverInterface;

class OSXDriver implements FsDriverInterface
{

    /**
     * @param DirectoryInterface $directory
     * @param DirectoryInterface $parent
     * @return SplFileInfo
     * @throws \Exception
     */
    public function createDirectory(DirectoryInterface $directory, DirectoryInterface $parent): SplFileInfo
    {
        $dirPath = $parent->getPath() . '/' . $parent->getName() . '/' . $directory->getName();

        if ( ! mkdir($dirPath, 0777, true)) {
            throw new \Exception('There was an error creating the directory');
        }

        return new SplFileInfo($dirPath);
    }

    /**
     * @param DirectoryInterface $directory
     * @return SplFileInfo
     * @throws \Exception
     */
    public function createRootDirectory(DirectoryInterface $directory): SplFileInfo
    {
        $dirPath = $directory->getPath() . '/' . $directory->getName();

        if ( ! mkdir($dirPath, 0777, true)) {
            throw new \Exception('There was an error creating the directory');
        }

        return new SplFileInfo($dirPath);
    }

    /**
     * @param DirectoryInterface $directory
     * @return bool
     */
    public function deleteDirectory(DirectoryInterface $directory): bool
    {
        $filePath = $directory->getPath() . '/' . $directory->getName();
        return rmdir($filePath);
    }

    /**
     * @param FileInterface $file
     * @param DirectoryInterface $parent
     * @return SplFileInfo
     * @throws \Exception
     */
    public function createFile(FileInterface $file, DirectoryInterface $parent): SplFileInfo
    {
        $filePath = $parent->getPath() . '/' . $parent->getName() . '/' . $file->getName();

        if ( ! touch($filePath)) {
            throw new \Exception('There was an error creating the file');
        }

        return new SplFileInfo($filePath);
    }

    /**
     * @param FileInterface $file
     * @return bool
     */
    public function deleteFile(FileInterface $file): bool
    {
        $filePath = $file->getPath() . '/' . $file->getName();
        return unlink($filePath);
    }

    /**
     * @param DirectoryInterface $directory
     * @return int
     */
    public function countDirectories(DirectoryInterface $directory): int
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

    /**
     * @param DirectoryInterface $directory
     * @return int
     */
    public function countFiles(DirectoryInterface $directory): int
    {
        $fileCount = 0;

        $directoryIterator = new \DirectoryIterator($directory->getPath() . '/' . $directory->getName());

        foreach ($directoryIterator as $item) {
            if ($item->isFile()) {
                $fileCount++;
            }
        }
        return $fileCount;
    }

    /**
     * @param DirectoryInterface $directory
     * @return FileInterface[]
     */
    public function listFiles(DirectoryInterface $directory): array
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

    /**
     * @param DirectoryInterface $directory
     * @return DirectoryInterface[]
     */
    public function listDirectories(DirectoryInterface $directory): array
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

    /**
     * @param DirectoryInterface $directory
     * @return int
     */
    public function directorySize(DirectoryInterface $directory): int
    {
        $size = 0;
        $dirPath = $directory->getPath() . '/' . $directory->getName();
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dirPath));
        foreach ($iterator as $info) {
            if ($info->isFile()) {
                $size += $info->getSize();
            }
        }
        return $size;    }

    /**
     * @param DirectoryInterface $directory
     * @param string $newname
     * @return SplFileInfo
     * @throws \Exception
     */
    public function renameDirectory(DirectoryInterface $directory, string $newname): SplFileInfo
    {
        $oldname = $directory->getPath() . '/' . $directory->getName();
        $replace = $directory->getPath() . '/' . $newname;

        if ( ! rename($oldname, $replace)) {
            throw new \Exception('There was an error renaming the file');
        }

        return new SplFileInfo($replace);
    }

    /**
     * @param FileInterface $file
     * @param string $newname
     * @return SplFileInfo
     * @throws \Exception
     */
    public function renameFile(FileInterface $file, string $newname): SplFileInfo
    {
        $oldname = $file->getPath() . '/' . $file->getName();
        $replace = $file->getPath() . '/' . $newname;

        if ( ! rename($oldname, $replace)) {
            throw new \Exception('There was an error renaming the file');
        }

        return new SplFileInfo($replace);
    }

    /**
     * @param FileInterface $file
     * @return SplFileInfo
     * @throws \Exception
     */
    public function updateFile(FileInterface $file): SplFileInfo
    {
        $filePath = $file->getPath() . '/' . $file->getName();

        if ( ! touch($filePath)) {
            throw new \Exception('There was an error updating the file');
        }

        clearstatcache();
        return new SplFileInfo($filePath);
    }
}