<?php

namespace Tsc\CatStorageSystem\Factories;

use Tsc\CatStorageSystem\File;
use Tsc\CatStorageSystem\FileInterface;

class FileFactory
{
    /**
     * @return FileInterface
     */
    static function create(){
        return new File();
    }
}