<?php

namespace Tsc\CatStorageSystem\Factories;

use Tsc\CatStorageSystem\Contracts\FileSystemInterface;
use Tsc\CatStorageSystem\OSXFileSystem;

class FSFactory
{
    /**
     * @return FileSystemInterface
     */
    static function create(){
        return new OSXFileSystem;
    }
}