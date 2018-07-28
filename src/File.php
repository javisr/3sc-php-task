<?php

namespace Tsc\CatStorageSystem;


use DateTimeInterface;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     *
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    /**
     * @param DateTimeInterface $created
     *
     * @return $this
     */
    public function setCreatedTime(DateTimeInterface $created)
    {
        $this->createdTime = $created;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getModifiedTime()
    {
        return $this->modifiedTime;
    }

    /**
     * @param DateTimeInterface $modified
     *
     * @return $this
     */
    public function setModifiedTime(DateTimeInterface $modified)
    {
        $this->modifiedTime = $modified;
        return $this;
    }

    /**
     * @return DirectoryInterface
     */
    public function getParentDirectory()
    {
        return $this->parentDirectory;
    }

    /**
     * @param DirectoryInterface $parent
     *
     * @return $this
     */
    public function setParentDirectory(DirectoryInterface $parent)
    {
        $this->parentDirectory = $parent;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->parentDirectory->getPath() . '/' . $this->parentDirectory->getName() ;
    }
}