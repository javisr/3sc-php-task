<?php

namespace Tsc\CatStorageSystem\FSUtils;

use Tsc\CatStorageSystem\FSUtils\Contracts\FsDeleteInterface;

class DirDelete implements FsDeleteInterface
{
    public function delete(string $filePath): bool
    {
        return rmdir($filePath);
    }
}