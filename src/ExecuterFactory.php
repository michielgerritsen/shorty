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

namespace ControlAltDelete\Shorty;

use ControlAltDelete\Shorty\Contracts\ExecuterInterface;
use ControlAltDelete\Shorty\Contracts\StorageInterface;
use ControlAltDelete\Shorty\Executers\LocalExecuter;

class ExecuterFactory
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

    public function build(string $type): ExecuterInterface
    {
        $name = 'ControlAltDelete\\Shorty\\Executers\\' . ucfirst($type) . 'Executer';
        $instance = app()->get($name);

        return $instance;
    }
}