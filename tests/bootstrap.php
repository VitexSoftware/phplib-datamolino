<?php

namespace Test\Datamolino;
/**
 * @author Vítězslav Dvořák <info@vitexsoftware.cz>
 */

define('EASE_LOGGER', 'syslog');

$autoloadScript = 'vendor/autoload.php';
$configuration  = 'Examples/config.php';

if(!file_exists($autoloadScript)){
    $autoloadScript = '../'.$autoloadScript;
    $configuration = '../'.$configuration;
}
include_once $autoloadScript;
include_once $configuration;

