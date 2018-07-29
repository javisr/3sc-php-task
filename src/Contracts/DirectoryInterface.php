<?php

namespace Tsc\CatStorageSystem\Contracts;

use \DateTimeInterface;

interface DirectoryInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name): DirectoryInterface;

    /**
     * @return DateTimeInterface
     */
    public function getCreatedTime(): DateTimeInterface;

    /**
     * @param DateTimeInterface $created
     *
     * @return $this
     */
    public function setCreatedTime(DateTimeInterface $created): DirectoryInterface;

    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path): DirectoryInterface;
}
