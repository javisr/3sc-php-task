<?php

namespace Tsc\CatStorageSystem;

use \SplFileInfo;
use Tsc\CatStorageSystem\Contracts\FileInterface;
use Tsc\CatStorageSystem\Contracts\DirectoryInterface;
use Tsc\CatStorageSystem\Contracts\FileSystemInterface;
use Tsc\CatStorageSystem\Factories\DirectoryFactory;
use Tsc\CatStorageSystem\Factories\FileFactory;

class OSXFileSystem implements FileSystemInterface
{

    /**
     * @param FileInterface $file
     * @param DirectoryInterface $parent
     *
     * @return FileInterface
     */
    public function createFile(FileInterface $file, DirectoryInterface $parent): FileInterface
    {
        $filePath = $parent->getPath() . '/' . $parent->getName() . '/' . $file->getName();
        touch($filePath);
        //  TODO: throw an exception when touch return false

        $fileInfo = new SplFileInfo($filePath);
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
        touch($filePath, $file->getModifiedTime()->getTimestamp());
        //  TODO: throw an exception when touch return false
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
        rename($file->getPath() . '/' . $file->getName(), $file->getPath() . '/' . $newName);
        //  TODO: throw an exception when rename fails

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
        return unlink($file->getPath() . '/' . $file->getName());
        //  TODO: throw an exception when unlink fails
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return DirectoryInterface
     */
    public function createRootDirectory(DirectoryInterface $directory): DirectoryInterface
    {
        $dirPath = $directory->getPath() . '/' . $directory->getName();

        mkdir($dirPath, 0777);
        //  TODO: throw an exception when mkdir fails

        return $directory;
    }

    /**
     * @param DirectoryInterface $directory
     * @param DirectoryInterface $parent
     *
     * @return DirectoryInterface
     */
    public function createDirectory( DirectoryInterface $directory, DirectoryInterface $parent): DirectoryInterface
    {
        $parentPath = $parent->getPath() . '/' . $parent->getName();
        //TODO?  $directory->getPath() if it has a relative path, create all subfolders
        $dirPath = $parentPath . '/' . $directory->getName();
        mkdir($dirPath, 0777);
        //  TODO: throw an exception when mkdir fails

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
        //  TODO: throw an exception when rmdir fails
        return rmdir($dir);
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

        rename($old, $new);
        //  TODO: throw an exception when rename fails

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

        $fn = function ($item) use (&$dirCount, $directory){
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

        $fn = function ($item) use (&$fileCount, $directory){
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

        $fn = function ($item) use (&$dirs, $directory){
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

        $fn = function ($item) use (&$files, $directory){
            if (!$item->isDir() && ! $item->isDot()) {
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

    protected function runDirectory($directory, $fn){
        $directoryIterator = new \DirectoryIterator($directory->getPath() . '/' . $directory->getName());

       foreach ($directoryIterator as $item) {
            $fn($item);
        }
    }

}