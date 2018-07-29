<?php

namespace Tsc\CatStorageSystem;

use PHPUnit\Framework\TestCase;
use Tsc\CatStorageSystem\Contracts\FileInterface;

class FileInterfaceTest extends TestCase {

    public function test_it_creates_a_new_instance() {
        $stub = $this->createMock(FileInterface::class);
        $this->assertTrue($stub instanceof FileInterface);
    }
}
