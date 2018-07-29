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

class RenameFileCommand extends Command
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

        $root = DirectoryFactory::create()->setPath('.')->setName('images');
        $file = FileFactory::create()
            ->setParentDirectory($root)
            ->setName($oldName);

        FSFactory::create()->renameFile($file, $newName);

        $output->writeln($oldName .' is now renamed to ' . $file->getName());
    }
}