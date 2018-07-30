<?php

namespace Tsc\CatStorageSystem;

use Tsc\CatStorageSystem\Contracts\FileInterface;
use Tsc\CatStorageSystem\Contracts\DirectoryInterface;
use Tsc\CatStorageSystem\Contracts\FileSystemInterface;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsCreatorInterface;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsDeleteInterface;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsDirCounterInterface;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsDirFileCounterInterface;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsDirFileListInterface;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsDirListInterface;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsDirSizeInterface;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsFileCreatorInterface;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsRenameInterface;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsUpdateInterface;

class FileSystem implements FileSystemInterface
{
    /**
     * @var FsFileCreatorInterface
     */
    protected $fileCreator;

    /**
     * @var FsUpdateInterface
     */
    protected $fileUpdater;

    /**
     * @var FsRenameInterface
     */
    protected $fileRenamer;

    /**
     * @var FsCreatorInterface
     */
    protected $dirCreator;

    /**
     * @var FsDeleteInterface
     */
    protected $fileDeleter;

    /**
     * @var FsDeleteInterface
     */
    protected $dirDeleter;

    /**
     * @var FsRenameInterface
     */
    protected $dirRenamer;

    /**
     * @var FsDirSizeInterface
     */
    protected $dirSizeCalculator;

    /**
     * @var FsDirFileCounterInterface
     */
    protected $dirFileCounter;

    /**
     * @var FsDirCounterInterface
     */
    protected $dirCounter;

    /**
     * @var FsDirListInterface
     */
    protected $dirListHelper;

    /**
     * @var FsDirFileListInterface
     */
    protected $dirFileListHelper;

    /**
     * @param $fileCreator
     * @return $this
     */
    public function setFileCreator($fileCreator)
    {
        $this->fileCreator = $fileCreator;
        return $this;
    }

    /**
     * @param $fileUpdater
     * @return $this
     */
    public function setFileUpdater($fileUpdater)
    {
        $this->fileUpdater = $fileUpdater;
        return $this;
    }

    /**
     * @param $fileRenamer
     * @return $this
     */
    public function setFileRenamer($fileRenamer)
    {
        $this->fileRenamer = $fileRenamer;
        return $this;
    }

    /**
     * @param $fileDeleter
     * @return $this
     */
    public function setFileDeleter($fileDeleter)
    {
        $this->fileDeleter = $fileDeleter;
        return $this;
    }

    /**
     * @param $dirDeleter
     * @return $this
     */
    public function setDirDeleter($dirDeleter)
    {
        $this->dirDeleter = $dirDeleter;
        return $this;
    }

    /**
     * @param $dirRenamer
     * @return $this
     */
    public function setDirRenamer($dirRenamer)
    {
        $this->dirRenamer = $dirRenamer;
        return $this;
    }

    /**
     * @param FsCreatorInterface $dirCreator
     * @return FileSystem
     */
    public function setDirCreator(FsCreatorInterface $dirCreator)
    {
        $this->dirCreator = $dirCreator;
        return $this;
    }

    /**
     * @param FsDirSizeInterface $dirSizeCalculator
     * @return FileSystem
     */
    public function setDirSizeCalculator(FsDirSizeInterface $dirSizeCalculator)
    {
        $this->dirSizeCalculator = $dirSizeCalculator;
        return $this;
    }

    /**
     * @param FsDirFileCounterInterface $dirFileCounter
     * @return FileSystem
     */
    public function setDirFileCounter(FsDirFileCounterInterface $dirFileCounter)
    {
        $this->dirFileCounter = $dirFileCounter;
        return $this;
    }

    /**
     * @param FsDirListInterface $dirListHelper
     * @return FileSystem
     */
    public function setDirListHelper(FsDirListInterface $dirListHelper)
    {
        $this->dirListHelper = $dirListHelper;
        return $this;
    }

    /**
     * @param FsDirFileListInterface $dirFileListHelper
     * @return FileSystem
     */
    public function setDirFileListHelper(FsDirFileListInterface $dirFileListHelper)
    {
        $this->dirFileListHelper = $dirFileListHelper;
        return $this;
    }

    /**
     * @param FsDirCounterInterface $dirCounter
     * @return FileSystem
     */
    public function setDirCounter(FsDirCounterInterface $dirCounter)
    {
        $this->dirCounter = $dirCounter;
        return $this;
    }

    /**
     * @param FileInterface $file
     * @param DirectoryInterface $parent
     *
     * @return FileInterface
     */
    public function createFile(FileInterface $file, DirectoryInterface $parent): FileInterface
    {
        $fileInfo = $this->fileCreator->create($file, $parent);
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
        $filePath = $file->getPath() . '/' . $file->getName();
        $this->fileUpdater->update($filePath);
        clearstatcache();
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
        $this->fileRenamer->rename($file, $newName);
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
        return $this->fileDeleter->delete($file);
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return DirectoryInterface
     */
    public function createRootDirectory(DirectoryInterface $directory): DirectoryInterface
    {
        $this->dirCreator->create($directory);
        return $directory;
    }

    /**
     * @param DirectoryInterface $directory
     * @param DirectoryInterface $parent
     *
     * @return DirectoryInterface
     */
    public function createDirectory(DirectoryInterface $directory, DirectoryInterface $parent): DirectoryInterface
    {
        $this->dirCreator->create($directory, $parent);
        return $directory;
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return bool
     */
    public function deleteDirectory(DirectoryInterface $directory): bool
    {
        return $this->dirDeleter->delete($directory);
    }

    /**
     * @param DirectoryInterface $directory
     * @param string $newName
     *
     * @return DirectoryInterface
     */
    public function renameDirectory(DirectoryInterface $directory, $newName): DirectoryInterface
    {
        $this->fileRenamer->rename($directory, $newName);
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
        return $this->dirCounter->calculate($directory);
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return int
     */
    public function getFileCount(DirectoryInterface $directory): int
    {
        return $this->dirFileCounter->calculate($directory);
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return int
     */
    public function getDirectorySize(DirectoryInterface $directory): int
    {
        $path = $directory->getPath() . '/' . $directory->getName();
        return $this->dirSizeCalculator->calculate($path);
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return DirectoryInterface[]
     */
    public function getDirectories(DirectoryInterface $directory): array
    {
        return $this->dirListHelper->list($directory);
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return FileInterface[]
     */
    public function getFiles(DirectoryInterface $directory): array
    {
        return $this->dirFileListHelper->list($directory);
    }
}