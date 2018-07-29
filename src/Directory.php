<?php

namespace Tsc\CatStorageSystem;


use DateTimeInterface;
use Tsc\CatStorageSystem\Contracts\DirectoryInterface;

class Directory implements DirectoryInterface
{

    private $name;

    private $createdTime;

    private $path;

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
    public function setName($name): DirectoryInterface
    {
        $this->name = $name;
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
    public function setCreatedTime(DateTimeInterface $created): DirectoryInterface
    {
        $this->createdTime = $created;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path): DirectoryInterface
    {
        $this->path = $path;
        return $this;
    }
}