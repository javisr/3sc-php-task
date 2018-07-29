<?php

namespace Tsc\CatStorageSystem\Contracts;

interface FileSystemInterface
{
    /**
     * @param FileInterface $file
     * @param DirectoryInterface $parent
     *
     * @return FileInterface
     */
    public function createFile(FileInterface $file, DirectoryInterface $parent): FileInterface;

    /**
     * @param FileInterface $file
     *
     * @return FileInterface
     */
    public function updateFile(FileInterface $file): FileInterface;

    /**
     * @param FileInterface $file
     * @param string $newName
     *
     * @return FileInterface
     */
    public function renameFile(FileInterface $file, $newName): FileInterface;

    /**
     * @param FileInterface $file
     *
     * @return bool
     */
    public function deleteFile(FileInterface $file): bool;

    /**
     * @param DirectoryInterface $directory
     *
     * @return DirectoryInterface
     */
    public function createRootDirectory(DirectoryInterface $directory): DirectoryInterface;

    /**
     * @param DirectoryInterface $directory
     * @param DirectoryInterface $parent
     *
     * @return DirectoryInterface
     */
    public function createDirectory(DirectoryInterface $directory, DirectoryInterface $parent): DirectoryInterface;

    /**
     * @param DirectoryInterface $directory
     *
     * @return bool
     */
    public function deleteDirectory(DirectoryInterface $directory): bool;

    /**
     * @param DirectoryInterface $directory
     * @param string $newName
     *
     * @return DirectoryInterface
     */
    public function renameDirectory(DirectoryInterface $directory, $newName): DirectoryInterface;

    /**
     * @param DirectoryInterface $directory
     *
     * @return int
     */
    public function getDirectoryCount(DirectoryInterface $directory): int;

    /**
     * @param DirectoryInterface $directory
     *
     * @return int
     */
    public function getFileCount(DirectoryInterface $directory): int;

    /**
     * @param DirectoryInterface $directory
     *
     * @return int
     */
    public function getDirectorySize(DirectoryInterface $directory): int;

    /**
     * @param DirectoryInterface $directory
     *
     * @return DirectoryInterface[]
     */
    public function getDirectories(DirectoryInterface $directory): array;

    /**
     * @param DirectoryInterface $directory
     *
     * @return FileInterface[]
     */
    public function getFiles(DirectoryInterface $directory): array;
}
