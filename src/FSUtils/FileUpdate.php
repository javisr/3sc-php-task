<?php

namespace Tsc\CatStorageSystem\FSUtils;

use SplFileInfo;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsCreatorInterface;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsUpdateInterface;

class FileUpdate implements FsUpdateInterface
{

    public function update(string $filePath): SplFileInfo
    {
        if ( ! touch($filePath)) {
            throw new \Exception('There was an error updating the file');
        }

        clearstatcache();
        return new SplFileInfo($filePath);
    }
}