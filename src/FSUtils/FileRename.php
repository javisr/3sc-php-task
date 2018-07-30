<?php

namespace Tsc\CatStorageSystem\FSUtils;

use SplFileInfo;
use Tsc\CatStorageSystem\Contracts\DirectoryInterface;
use Tsc\CatStorageSystem\Directory;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsCreatorInterface;
use Tsc\CatStorageSystem\FSUtils\Contracts\FsRenameInterface;

class FileRename implements FsRenameInterface
{

    /**
     * @param FileInterface|DirectoryInterface $oldname
     * @param string $newname
     * @return SplFileInfo
     * @throws \Exception
     */
    public function rename($file, string $newName): SplFileInfo
    {
        $oldname = $file->getPath() . '/' . $file->getName();
        $newname = $file->getPath() . '/' . $newName;

        if ( ! rename($oldname, $newname)) {
            throw new \Exception('There was an error renaming the file');
        }

        return new SplFileInfo($newname);
    }
}