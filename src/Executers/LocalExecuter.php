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

class LocalExecuter implements ExecuterInterface
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var Process
     */
    private $process;

    public function __construct(
        StorageInterface $storage
    ) {
        $this->storage = $storage;
    }

    /**
     * @param string $name
     */
    public function run(string $name)
    {
        $config = $this->storage->get($name);

        $command = $config['command'];
        if (count($_SERVER['argv']) > 1) {
            array_shift($_SERVER['argv']);
            $command .= ' ' . implode(' ', $_SERVER['argv']);
        }

        $process = new Process($command, $config['path']);
        $process->setTty(true);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }
}