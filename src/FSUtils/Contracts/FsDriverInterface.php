<?php

namespace Tsc\CatStorageSystem\FSUtils\Contracts;

use SplFileInfo;
use Tsc\CatStorageSystem\Contracts\DirectoryInterface;
use Tsc\CatStorageSystem\Contracts\FileInterface;

interface FsDriverInterface
{
    /**
     * @param DirectoryInterface $directory
     * @param DirectoryInterface $parent
     * @return SplFileInfo
     * @throws \Exception
     */
    public function createDirectory(DirectoryInterface $directory, DirectoryInterface $parent): SplFileInfo;

    /**
     * @param DirectoryInterface $directory
     * @return SplFileInfo
     * @throws \Exception
     */
    public function createRootDirectory(DirectoryInterface $directory): SplFileInfo;

    /**
     * @param DirectoryInterface $directory
     * @return bool
     */
    public function deleteDirectory(DirectoryInterface $directory): bool;

    /**
     * @param FileInterface $file
     * @param DirectoryInterface $parent
     * @return SplFileInfo
     * @throws \Exception
     */
    public function createFile(FileInterface $file, DirectoryInterface $parent): SplFileInfo;

    /**
     * @param FileInterface $file
     * @return bool
     */
    public function deleteFile(FileInterface $file): bool;

    /**
     * @param DirectoryInterface $directory
     * @return int
     */
    public function countDirectories(DirectoryInterface $directory): int;

    /**
     * @param DirectoryInterface $directory
     * @return int
     */
    public function countFiles(DirectoryInterface $directory): int;

    /**
     * @param DirectoryInterface $directory
     * @return FileInterface[]
     */
    public function listFiles(DirectoryInterface $directory): array ;

    /**
     * @param DirectoryInterface $directory
     * @return DirectoryInterface[]
     */
    public function listDirectories(DirectoryInterface $directory): array ;

    /**
     * @param DirectoryInterface $directory
     * @return int
     */
    public function directorySize(DirectoryInterface $directory): int;

    /**
     * @param DirectoryInterface $directory
     * @param string $newname
     * @return SplFileInfo
     */
    public function renameDirectory(DirectoryInterface $directory, string $newname): SplFileInfo;

    /**
     * @param FileInterface $file
     * @param string $newname
     * @return SplFileInfo
     */
    public function renameFile(FileInterface$file, string $newname): SplFileInfo;

    /**
     * @param FileInterface $file
     * @return SplFileInfo
     */
    public function updateFile(FileInterface $file): SplFileInfo;

}