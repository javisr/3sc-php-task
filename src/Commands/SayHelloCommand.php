<?php
namespace Tsc\CatStorageSystem\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SayHelloCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('helloWorld')
            ->setDescription('It just says \'Hello :)\'');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello! :)');
    }
}