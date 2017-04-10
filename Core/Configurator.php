<?php

namespace Core;


class Configurator
{

    private static $currentConfiguration = NULL;

    public static function setCurrentConfiguration(Configuration $configuration) {
        self::$currentConfiguration = $configuration;
    }

    public static function getCurrentConfiguration(): Configuration {
        return self::$currentConfiguration;
    }

    public function parseConfiguration($configurationFile, $throwIfNotFound = FALSE): Configuration {
        if (!$throwIfNotFound) $parsed = @parse_ini_file($configurationFile, TRUE);
        else $parsed = parse_ini_file($configurationFile, TRUE);

        if ($parsed !== FALSE) return new Configuration($parsed);
        return new Configuration();
    }

    public function writeConfiguration($filename, Configuration $configuration) {
        $this->write_ini_file($configuration->all(), $filename, TRUE);

    }


    private function write_ini_file($assoc_arr, $path, $has_sections) {
        $content = '';

        if (!$handle = fopen($path, 'w'))
            return FALSE;

        $this->_write_ini_file_r($content, $assoc_arr, $has_sections);

        if (!fwrite($handle, $content))
            return FALSE;

        fclose($handle);

        return TRUE;
    }

    private function _write_ini_file_r(&$content, $assoc_arr, $has_sections) {
        foreach ($assoc_arr as $key => $val) {
            if (is_array($val)) {
                if ($has_sections) {
                    $content .= "[$key]\n";
                    $this->_write_ini_file_r($content, $val, FALSE);
                } else {
                    foreach ($val as $iKey => $iVal) {
                        if (is_bool($iVal)) {
                            $iVal = $iVal ? "true" : "false";
                        }
                        if (empty($iVal)) {
                            $iVal = '""';
                        }


                        if (is_int($iKey))
                            $content .= $key . "[] = $iVal\n";
                        else
                            $content .= $key . "[$iKey] = $iVal\n";
                    }
                }
            } else {
                if (is_bool($val)) {
                    $val = $val ? "true" : "false";
                }

                if (empty($val)) {
                    $val = '""';
                }
                $content .= "$key = $val\n";
            }
        }
    }


}