<?php

namespace Tsc\CatStorageSystem\FSUtils;

use Tsc\CatStorageSystem\FSUtils\Contracts\FsDeleteInterface;

class DirDelete implements FsDeleteInterface
{
    public function delete($directory): bool
    {
        $filePath = $directory->getPath() . '/' . $directory->getName();
        return rmdir($filePath);
    }
}