#!/usr/bin/php
<?php
/**
 *    ______            __             __
 *   / ____/___  ____  / /__________  / /
 *  / /   / __ \/ __ \/ __/ ___/ __ \/ /
 * / /___/ /_/ / / / / /_/ /  / /_/ / /
 * \______________/_/\__/_/   \____/_/
 *    /   |  / / /_
 *   / /| | / / __/
 *  / ___ |/ / /_
 * /_/ _|||_/\__/ __     __
 *    / __ \___  / /__  / /____
 *   / / / / _ \/ / _ \/ __/ _ \
 *  / /_/ /  __/ /  __/ /_/  __/
 * /_____/\___/_/\___/\__/\___/
 *
 */

require __DIR__ . '/../vendor/autoload.php';

$storage = new \ControlAltDelete\Shorty\Service\Storage;

$name = end(explode('/', $_SERVER['SCRIPT_FILENAME']));

$config = $storage->get($name);

$command = $config['path'] . '/' . $config['command'];

if (count($argv) > 1) {
    array_shift($argv);
    $command .= ' ' . implode(' ', $argv);
}

$process = new \Symfony\Component\Process\Process($command);
$process->setTty(true);
$process->run(function ($type, $buffer) {
    echo $buffer;
});