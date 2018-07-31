<?php

namespace Tsc\CatStorageSystem\Commands;

use Symfony\Component\Console\Command\Command;
use Tsc\CatStorageSystem\Contracts\DirectoryInterface;
use Tsc\CatStorageSystem\Contracts\FileSystemInterface;
use Tsc\CatStorageSystem\Factories\DirectoryFactory;
use Tsc\CatStorageSystem\FileSystem;
use Tsc\CatStorageSystem\FSUtils\OSXDriver;

abstract class AbstractCommand extends Command
{
    /**
     * @var FileSystemInterface
     */
    protected $fs;

    /**
     * @var DirectoryInterface
     */
    protected $root;

    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->root = DirectoryFactory::create()->setPath('.')->setName('images');

        $this->fs = new FileSystem(new OSXDriver());
    }
}