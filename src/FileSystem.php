<?php

namespace Tsc\CatStorageSystem;

use Tsc\CatStorageSystem\Contracts\FileInterface;
use Tsc\CatStorageSystem\Contracts\DirectoryInterface;
use Tsc\CatStorageSystem\Contracts\FileSystemInterface;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsDriverInterface;

class FileSystem implements FileSystemInterface
{
    /**
     * @var FsDriverInterface
     */
    protected $driver;

    public function __construct(FsDriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param FileInterface $file
     * @param DirectoryInterface $parent
     * @return FileInterface
     * @throws \Exception
     */
    public function createFile(FileInterface $file, DirectoryInterface $parent): FileInterface
    {
        $fileInfo = $this->driver->createFile($file, $parent);
        return $file
            ->setParentDirectory($parent)
            ->setSize($fileInfo->getSize())
            ->setCreatedTime((new \DateTime())
                ->setTimestamp($fileInfo->getMTime()))
            ->setModifiedTime((new \DateTime())
                ->setTimestamp($fileInfo->getMTime()));
    }

    /**
     * @param FileInterface $file
     *
     * @return FileInterface
     */
    public function updateFile(FileInterface $file): FileInterface
    {
        $this->driver->updateFile($file);
        return $file;
    }

    /**
     * @param FileInterface $file
     * @param string $newName
     *
     * @return FileInterface
     */
    public function renameFile(FileInterface $file, $newName): FileInterface
    {
        $this->driver->renameFile($file, $newName);
        $file->setName($newName);
        return $file;
    }

    /**
     * @param FileInterface $file
     *
     * @return bool
     */
    public function deleteFile(FileInterface $file): bool
    {
        return $this->driver->deleteFile($file);
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return DirectoryInterface
     * @throws \Exception
     */
    public function createRootDirectory(DirectoryInterface $directory): DirectoryInterface
    {
        $this->driver->createRootDirectory($directory);
        return $directory;
    }

    /**
     * @param DirectoryInterface $directory
     * @param DirectoryInterface $parent
     *
     * @return DirectoryInterface
     * @throws \Exception
     */
    public function createDirectory(DirectoryInterface $directory, DirectoryInterface $parent): DirectoryInterface
    {
        $this->driver->createDirectory($directory, $parent);
        return $directory;
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return bool
     */
    public function deleteDirectory(DirectoryInterface $directory): bool
    {
        return $this->driver->deleteDirectory($directory);
    }

    /**
     * @param DirectoryInterface $directory
     * @param string $newName
     *
     * @return DirectoryInterface
     */
    public function renameDirectory(DirectoryInterface $directory, $newName): DirectoryInterface
    {
        $this->driver->renameDirectory($directory, $newName);
        $directory->setName($newName);
        return $directory;
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return int
     */
    public function getDirectoryCount(DirectoryInterface $directory): int
    {
        return $this->driver->countDirectories($directory);
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return int
     */
    public function getFileCount(DirectoryInterface $directory): int
    {
        return $this->driver->countFiles($directory);
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return int
     */
    public function getDirectorySize(DirectoryInterface $directory): int
    {
        return $this->driver->directorySize($directory);
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return DirectoryInterface[]
     */
    public function getDirectories(DirectoryInterface $directory): array
    {
        return $this->driver->listDirectories($directory);
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return FileInterface[]
     */
    public function getFiles(DirectoryInterface $directory): array
    {
        return $this->driver->listFiles($directory);
    }
}