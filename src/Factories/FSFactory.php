<?php

namespace Tsc\CatStorageSystem\Factories;

use Tsc\CatStorageSystem\Contracts\FileSystemInterface;
use Tsc\CatStorageSystem\FileSystem;

class FSFactory
{
    /**
     * @return FileSystemInterface
     */
    static function create(){
        return new FileSystem;
    }
}