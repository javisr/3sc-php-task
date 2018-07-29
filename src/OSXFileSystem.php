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
    public function createFile(FileInterface $file, DirectoryInterface $parent)
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
    public function updateFile(FileInterface $file)
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
    public function renameFile(FileInterface $file, $newName)
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
    public function deleteFile(FileInterface $file)
    {
        return unlink($file->getPath() . '/' . $file->getName());
        //  TODO: throw an exception when unlink fails
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return DirectoryInterface
     */
    public function createRootDirectory(DirectoryInterface $directory)
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
    public function createDirectory(
        DirectoryInterface $directory, DirectoryInterface $parent
    )
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
    public function deleteDirectory(DirectoryInterface $directory)
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
    public function renameDirectory(DirectoryInterface $directory, $newName)
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
    public function getDirectoryCount(DirectoryInterface $directory)
    {
        $dirCount = 0;
        $directoryIterator = new \DirectoryIterator($directory->getPath() . '/' . $directory->getName());

        foreach ($directoryIterator as $item) {
            if ($item->isDir() && ! $item->isDot()) {
                $dirCount++;
            }
        }

        return $dirCount;
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return int
     */
    public function getFileCount(DirectoryInterface $directory)
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
     *
     * @return int
     */
    public function getDirectorySize(DirectoryInterface $directory)
    {
        $size = 0;
        $path = $directory->getPath() . '/' . $directory->getName();
        $rdi = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($rdi);
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
    public function getDirectories(DirectoryInterface $directory)
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
     *
     * @return FileInterface[]
     */
    public function getFiles(DirectoryInterface $directory)
    {
        $files = [];
        $directoryIterator = new \DirectoryIterator($directory->getPath() . '/' . $directory->getName());

        foreach ($directoryIterator as $item) {
            if ($item->isDir() && ! $item->isDot()) {
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