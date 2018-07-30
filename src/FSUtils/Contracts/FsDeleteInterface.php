<?php

namespace Tsc\CatStorageSystem\FSUtils\Contracts;

interface FsDeleteInterface
{
    public function delete(string $filePath): bool;
}