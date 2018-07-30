<?php

namespace Tsc\CatStorageSystem\FSUtils;

use Tsc\CatStorageSystem\FSUtils\Contracts\FsDeleteInterface;

class FileDelete implements FsDeleteInterface
{
    public function delete(string $filePath): bool
    {
        return unlink($filePath);
    }
}