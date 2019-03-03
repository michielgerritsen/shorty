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

declare(strict_types=1);

namespace ControlAltDelete\Shorty\Contracts;

interface SymlinkInterface
{
    /**
     * @param string $name
     * @param string $type
     * @return bool
     */
    public function create(string $name, string $type): bool;

    /**
     * @param string $name
     * @return bool
     */
    public function remove(string $name): bool;
}