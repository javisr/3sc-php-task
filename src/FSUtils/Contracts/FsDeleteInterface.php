<?php

namespace Tsc\CatStorageSystem\FSUtils\Contracts;

use Tsc\CatStorageSystem\Contracts\DirectoryInterface;
use Tsc\CatStorageSystem\Contracts\FileInterface;

interface FsDeleteInterface
{
    /**
     * @param FileInterface|DirectoryInterface $file
     * @return bool
     */
    public function delete($file): bool;
}