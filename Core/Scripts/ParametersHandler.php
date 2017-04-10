<?php

namespace Core\Scripts;


use Composer\Script\Event;
use Core\Configuration;
use Core\Configurator;

class ParametersHandler
{
    public static function parseConfig(Event $event) {
        $configurator = new Configurator();

        $extras = $event->getComposer()->getPackage()->getExtra();
        $configFile = $extras["config-file"];

        $config = $configurator->parseConfiguration($configFile);

        $distConfig = $configurator->parseConfiguration("$configFile.dist");
        $newConfig = new Configuration();
        $io = $event->getIO();
        foreach ($distConfig as $section => $parameters) {
            $alreadyExistingConfig = $config->get($section, []);

            self::checkArray($parameters, $alreadyExistingConfig, $io, $section);
            $newConfig->set($section, $alreadyExistingConfig);

        }
        $configurator->writeConfiguration($configFile, $newConfig);

        $io->write(
            [
                "<info>All Done !</info>",
                "Don't forget to add your commands to the 'command' file",
            ]

        );
    }

    public static function checkArray($newValuesArray, &$baseArray, &$io, $fullKey) {
        foreach ($newValuesArray as $key => $value) {
            if (!isset($baseArray[$key])) {
                if (!is_array($value)) {
                    $baseArray[$key] = $io->ask(sprintf('<question>%s</question> (<comment>%s</comment>): ', $fullKey . "." . $key, $value), $value);
                } else {
                    $baseArray[$key] = [];

                    self::checkArray($newValuesArray[$key], $baseArray[$key], $io, $fullKey . "." . $key);
                }
            }
        }

    }

}