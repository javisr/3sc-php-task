<?php

namespace Tsc\CatStorageSystem;


use DateTimeInterface;
use Tsc\CatStorageSystem\Contracts\FileInterface;
use Tsc\CatStorageSystem\Contracts\DirectoryInterface;

class File implements FileInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $size;

    /**
     * @var DateTimeInterface
     */
    private $createdTime;

    /**
     * @var DateTimeInterface
     */
    private $modifiedTime;

    /**
     * @var DirectoryInterface
     */
    private $parentDirectory;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name): FileInterface
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     *
     * @return $this
     */
    public function setSize($size): FileInterface
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedTime(): DateTimeInterface
    {
        return $this->createdTime;
    }

    /**
     * @param DateTimeInterface $created
     *
     * @return $this
     */
    public function setCreatedTime(DateTimeInterface $created): FileInterface
    {
        $this->createdTime = $created;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getModifiedTime(): DateTimeInterface
    {
        return $this->modifiedTime;
    }

    /**
     * @param DateTimeInterface $modified
     *
     * @return $this
     */
    public function setModifiedTime(DateTimeInterface $modified): FileInterface
    {
        $this->modifiedTime = $modified;
        return $this;
    }

    /**
     * @return DirectoryInterface
     */
    public function getParentDirectory(): DirectoryInterface
    {
        return $this->parentDirectory;
    }

    /**
     * @param DirectoryInterface $parent
     *
     * @return $this
     */
    public function setParentDirectory(DirectoryInterface $parent): FileInterface
    {
        $this->parentDirectory = $parent;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->parentDirectory->getPath() . '/' . $this->parentDirectory->getName() ;
    }
}