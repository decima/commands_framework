<?php

namespace Commands;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelloWorldCommand extends Command
{
    protected function configure() {
        $this
            ->setName('hello:world')
            ->setDescription('Say Hello World.')
            ->setHelp('This command says Hello World');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln([
            '=================',
            '     Hello World!',
            '=================',
        ]);


    }

}