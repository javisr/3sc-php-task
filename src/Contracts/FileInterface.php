<?php

namespace Tsc\CatStorageSystem\Contracts;

use \DateTimeInterface;

interface FileInterface
{
    /**
     * @return string
     */
    public function getName(): string ;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name): FileInterface;

    /**
     * @return int
     */
    public function getSize(): int;

    /**
     * @param int $size
     *
     * @return $this
     */
    public function setSize($size): FileInterface;

    /**
     * @return DateTimeInterface
     */
    public function getCreatedTime(): DateTimeInterface;

    /**
     * @param DateTimeInterface $created
     *
     * @return $this
     */
    public function setCreatedTime(DateTimeInterface $created): FileInterface;

    /**
     * @return DateTimeInterface
     */
    public function getModifiedTime(): DateTimeInterface;

    /**
     * @param DateTimeInterface $modified
     *
     * @return $this
     */
    public function setModifiedTime(DateTimeInterface $modified): FileInterface;

    /**
     * @return DirectoryInterface
     */
    public function getParentDirectory(): DirectoryInterface;

    /**
     * @param DirectoryInterface $parent
     *
     * @return $this
     */
    public function setParentDirectory(DirectoryInterface $parent): FileInterface;

    /**
     * @return string
     */
    public function getPath();
}
