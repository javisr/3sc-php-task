<?php

namespace Tsc\CatStorageSystem;

use Tsc\CatStorageSystem\Contracts\FileInterface;
use Tsc\CatStorageSystem\Contracts\DirectoryInterface;
use Tsc\CatStorageSystem\Contracts\FileSystemInterface;
use Tsc\CatStorageSystem\Factories\DirectoryFactory;
use Tsc\CatStorageSystem\Factories\FileFactory;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsCreatorInterface;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsDeleteInterface;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsRenameInterface;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsUpdateInterface;

class FileSystem implements FileSystemInterface
{
    /**
     * @var FsCreatorInterface
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
    public function setDirDirRenamer($dirRenamer)
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
     * @param FileInterface $file
     * @param DirectoryInterface $parent
     *
     * @return FileInterface
     */
    public function createFile(FileInterface $file, DirectoryInterface $parent): FileInterface
    {
        $filePath = $parent->getPath() . '/' . $parent->getName() . '/' . $file->getName();

        $fileInfo = $this->fileCreator->create($filePath);

        $creationDate = (new \DateTime())
            ->setTimestamp($fileInfo->getMTime());

        return $file
            ->setParentDirectory($parent)
            ->setSize($fileInfo->getSize())
            ->setCreatedTime($creationDate)
            ->setModifiedTime($creationDate);
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
        $oldPathName = $file->getPath() . '/' . $file->getName();
        $newPathName = $file->getPath() . '/' . $newName;

        $this->fileRenamer->rename($oldPathName, $newPathName);

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
        return $this->fileDeleter->delete($file->getPath() . '/' . $file->getName());
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return DirectoryInterface
     */
    public function createRootDirectory(DirectoryInterface $directory): DirectoryInterface
    {
        $dirPath = $directory->getPath() . '/' . $directory->getName();

        $this->dirCreator->create($dirPath, 0777);

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
        $parentPath = $parent->getPath() . '/' . $parent->getName();
        $dirPath = $parentPath . '/' . $directory->getName();

        $this->dirCreator->create($dirPath);

        return $directory;
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return bool
     */
    public function deleteDirectory(DirectoryInterface $directory): bool
    {
        $dir = $directory->getPath() . '/' . $directory->getName();

        return $this->dirDeleter->delete($dir);
    }

    /**
     * @param DirectoryInterface $directory
     * @param string $newName
     *
     * @return DirectoryInterface
     */
    public function renameDirectory(DirectoryInterface $directory, $newName): DirectoryInterface
    {
        $old = $directory->getPath() . '/' . $directory->getName();
        $new = $directory->getPath() . '/' . $newName;

        $this->dirRenamer->rename($old, $new);

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
        $dirCount = 0;

        $fn = function ($item) use (&$dirCount, $directory) {
            if ($item->isDir() && ! $item->isDot()) {
                $dirCount++;
            }
        };

        $this->runDirectory($directory, $fn);

        return $dirCount;
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return int
     */
    public function getFileCount(DirectoryInterface $directory): int
    {
        $fileCount = 0;

        $fn = function ($item) use (&$fileCount, $directory) {
            if ($item->isFile()) {
                $fileCount++;
            }
        };

        $this->runDirectory($directory, $fn);

        return $fileCount;
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return int
     */
    public function getDirectorySize(DirectoryInterface $directory): int
    {
        $size = 0;
        $path = $directory->getPath() . '/' . $directory->getName();
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        foreach ($iterator as $info) {
            if ($info->isFile()) {
                $size += $info->getSize();
            }
        }
        return $size;
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return DirectoryInterface[]
     */
    public function getDirectories(DirectoryInterface $directory): array
    {
        $dirs = [];

        $fn = function ($item) use (&$dirs, $directory) {
            if ($item->isDir() && ! $item->isDot()) {
                $dirs[] = DirectoryFactory::create()
                    ->setName($item->getFilename())
                    ->setPath($item->getPath())
                    ->setCreatedTime((new \DateTime())->setTimestamp($item->getCTime()));
            }
        };

        $this->runDirectory($directory, $fn);

        return $dirs;
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return FileInterface[]
     */
    public function getFiles(DirectoryInterface $directory): array
    {
        $files = [];

        $fn = function ($item) use (&$files, $directory) {
            if ( ! $item->isDir() && ! $item->isDot()) {
                $files[] = FileFactory::create()
                    ->setParentDirectory($directory)
                    ->setName($item->getFilename())
                    ->setSize($item->getSize())
                    ->setCreatedTime((new \DateTime())->setTimestamp($item->getCTime()));
            }
        };

        $this->runDirectory($directory, $fn);

        return $files;
    }

    protected function runDirectory($directory, $fn)
    {
        $directoryIterator = new \DirectoryIterator($directory->getPath() . '/' . $directory->getName());

        foreach ($directoryIterator as $item) {
            $fn($item);
        }
    }

}