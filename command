#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;




$application = new Application();

$configurator = new \Core\Configurator();
\Core\Configurator::setCurrentConfiguration($configurator->parseConfiguration("config/parameters.ini",true));


// ... register commands
$application->addCommands([
    new \Commands\HelloWorldCommand(),
    //TODO add your own commands here.
]);
$application->run();