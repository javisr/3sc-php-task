<?php

namespace Tsc\CatStorageSystem\FSUtils\Contracts;
use \SplFileInfo;

interface FsCreatorInterface
{
    public function create($directory): SplFileInfo;
}