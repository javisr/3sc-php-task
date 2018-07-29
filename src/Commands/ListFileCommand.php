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

class ListFileCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('dir:list:files')
            ->setDescription('List files inside a directory. If none is provided, root directory is used')
            ->addArgument('dir_name', InputArgument::OPTIONAL, 'Directory name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dirName = $input->getArgument('dir_name') ?   $input->getArgument('dir_name') : '';
        $root = DirectoryFactory::create()->setPath('./images')->setName($dirName);

        $fs = FSFactory::create();

        $files = $fs->getFiles($root);
        foreach ($files as $file) {
            $output->writeln($file->getName());
        }

        if($fs->getFileCount($root) === 0){
            $output->writeln('Not files found inside ' . $dirName);
        }
    }
}