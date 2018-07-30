<?php

namespace Tsc\CatStorageSystem\FSUtils\Contracts;
use \SplFileInfo;

interface FsRenameInterface
{
    public function rename($file, string $newname): SplFileInfo;
}