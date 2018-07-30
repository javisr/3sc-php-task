<?php

namespace Tsc\CatStorageSystem\FSUtils;

use Tsc\CatStorageSystem\FSUtils\Contracts\FsDeleteInterface;

class FileDelete implements FsDeleteInterface
{
    public function delete($file): bool
    {
        $filePath = $file->getPath() . '/' . $file->getName();
        return unlink($filePath);
    }
}