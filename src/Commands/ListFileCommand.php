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

class ListFileCommand extends AbstractCommand
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
        $dirName = $input->getArgument('dir_name') ? $input->getArgument('dir_name') : '';
        $dir = DirectoryFactory::create()
            ->setPath($this->root->getPath() . '/' . $this->root->getName())
            ->setName($dirName);

        $files = [];

        try {
            $files = $this->fs->getFiles($dir);
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }

        foreach ($files as $file) {
            $output->writeln($file->getName());
        }

        if ($this->fs->getFileCount($dir) === 0) {
            $output->writeln('Not files found inside ' . $dirName);
        }

    }


}