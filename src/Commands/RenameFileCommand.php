<?php

namespace Tsc\CatStorageSystem\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tsc\CatStorageSystem\Contracts\DirectoryInterface;
use Tsc\CatStorageSystem\Factories\DirectoryFactory;
use Tsc\CatStorageSystem\Factories\FileFactory;
use Tsc\CatStorageSystem\Factories\FSFactory;
use Symfony\Component\Console\Input\InputArgument;
use Tsc\CatStorageSystem\FSUtils\FileCreator;
use Tsc\CatStorageSystem\FSUtils\FileRename;
use Tsc\CatStorageSystem\FSUtils\FileUpdate;

class RenameFileCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('file:rename')
            ->setDescription('Renames a file')
            ->addArgument('old_name', InputArgument::REQUIRED, 'Old Name')
            ->addArgument('new_name', InputArgument::REQUIRED, 'New name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $oldName = $input->getArgument('old_name');
        $newName = $input->getArgument('new_name');

        $file = FileFactory::create()
            ->setParentDirectory($this->root)
            ->setName($oldName);

        $this->fs->renameFile($file, $newName);

        $output->writeln($oldName .' is now renamed to ' . $file->getName());
    }
}