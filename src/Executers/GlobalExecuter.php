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

namespace ControlAltDelete\Shorty\Executers;

use ControlAltDelete\Shorty\Contracts\ExecuterInterface;
use ControlAltDelete\Shorty\Contracts\StorageInterface;
use Symfony\Component\Process\Process;

class GlobalExecuter implements ExecuterInterface
{
    /**
     * @var StorageInterface
     */
    private $storage;

    public function __construct(
        StorageInterface $storage
    ) {
        $this->storage = $storage;
    }

    /**
     * @param string $name
     * @return void
     */
    public function run(string $name)
    {
        $config = $this->storage->get($name);

        if (!$path = $this->findPath($config['command'], getcwd())) {
            throw new \Exception('Command not found in the current path.');
        }

        $process = new Process('./' . $config['command']);
        $process->setWorkingDirectory($path);
        $process->setTty(true);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }

    private function findPath(string $command, string $path)
    {
        if ($path == '/') {
            return false;
        }

        if (!file_exists($path . '/' . $command)) {
            return $this->findPath($command, realpath($path . '/../'));
        }

        return $path;
    }
}