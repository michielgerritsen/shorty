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

namespace ControlAltDelete\Shorty\Service;

use ControlAltDelete\Shorty\Contracts\SymlinkInterface;

class Symlink implements SymlinkInterface
{
    /**
     * @param string $name
     * @param string $type
     * @return bool
     * @throws \Exception
     */
    public function create(string $name, string $type): bool
    {
        $path = $this->getPath();
        if (!is_writable($path)) {
            throw new \Exception('Unable to create symlink: Path (' . $path . $name . ') is not writable');
        }

        $target = $path . $name;
        if (!is_link($target)) {
            return false;
        }

        return symlink(
            __DIR__ . '/../' . ucfirst($type) . 'Command.php',
            $target
        );
    }

    /**
     * @param string $name
     * @return bool
     */
    public function remove(string $name): bool
    {
        $target = $this->getPath() . $name;
        if (!file_exists($target)) {
            return false;
        }

        $result = unlink($target);
        clearstatcache();

        return $result;
    }

    /**
     * @return string
     */
    private function getPath(): string
    {
        return $_SERVER['HOME'] . '/.composer/vendor/bin/';
    }
}