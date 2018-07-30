<?php

namespace Tsc\CatStorageSystem\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tsc\CatStorageSystem\Contracts\DirectoryInterface;
use Tsc\CatStorageSystem\Contracts\FileSystemInterface;
use Tsc\CatStorageSystem\Factories\DirectoryFactory;
use Tsc\CatStorageSystem\Factories\FileFactory;
use Tsc\CatStorageSystem\Factories\FSFactory;
use Symfony\Component\Console\Input\InputArgument;
use Tsc\CatStorageSystem\FileSystem;
use Tsc\CatStorageSystem\FSUtils\DirCounter;
use Tsc\CatStorageSystem\FSUtils\DirCreator;
use Tsc\CatStorageSystem\FSUtils\DirDelete;
use Tsc\CatStorageSystem\FSUtils\DirFileCounter;
use Tsc\CatStorageSystem\FSUtils\DirFileList;
use Tsc\CatStorageSystem\FSUtils\DirList;
use Tsc\CatStorageSystem\FSUtils\DirSizeCalculator;
use Tsc\CatStorageSystem\FSUtils\FileCreator;
use Tsc\CatStorageSystem\FSUtils\FileDelete;
use Tsc\CatStorageSystem\FSUtils\FileRename;
use Tsc\CatStorageSystem\FSUtils\FileUpdate;
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