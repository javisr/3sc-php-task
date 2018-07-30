<?php

namespace Tsc\CatStorageSystem\FSUtils;

use Tsc\CatStorageSystem\FSUtils\Contracts\FsDirSizeInterface;

class DirSizeCalculator implements FsDirSizeInterface
{

    /**
     * @param string $dirPath
     * @return int
     */
    public function calculate(string $dirPath): int
    {
        $size = 0;
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dirPath));
        foreach ($iterator as $info) {
            if ($info->isFile()) {
                $size += $info->getSize();
            }
        }
        return $size;
    }
}