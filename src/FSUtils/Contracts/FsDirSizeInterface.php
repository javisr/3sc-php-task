<?php

namespace Tsc\CatStorageSystem\FSUtils\Contracts;


interface FsDirSizeInterface
{
    /**
     * @param string $dirPath
     * @return int
     */
    public function calculate(string $dirPath): int;
}