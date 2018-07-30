<?php

namespace Tsc\CatStorageSystem\FSUtils\Contracts;
use \SplFileInfo;

interface FsRenameInterface
{
    public function rename(string $oldname, string $newname): SplFileInfo;
}