<?php

namespace Tsc\CatStorageSystem\FSUtils\Contracts;
use \SplFileInfo;

interface FsUpdateInterface
{
    public function update(string $filePath): SplFileInfo;
}