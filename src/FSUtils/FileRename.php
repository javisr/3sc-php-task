<?php

namespace Tsc\CatStorageSystem\FSUtils;

use SplFileInfo;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsCreatorInterface;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsRenameInterface;

class FileRename implements FsRenameInterface
{

    public function rename(string $oldname, string $newname): SplFileInfo
    {

        if ( ! rename($oldname, $newname)) {
            throw new \Exception('There was an error renaming the file');
        }

        return new SplFileInfo($newname);
    }
}