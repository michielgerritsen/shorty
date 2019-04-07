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

class Dictionary
{
    const COMMAND_ADDED = 'Command %s added. Try it out!';
    const COMMAND_ALREADY_EXISTS = 'The command %s already exists. Use `shorty fix` to recreate it';
    const COMPOSER_NOT_FOUND = 'The .composer/vendor/bin bin is not in your $PATH. Please add it.';
    const COMMAND_REMOVED = 'The [%s] shortcut is succesfully removed';
}