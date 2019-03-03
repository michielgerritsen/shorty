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

namespace ControlAltDelete\Shorty\Commands;

use ControlAltDelete\Shorty\Contracts\StorageInterface;
use ControlAltDelete\Shorty\Service\Symlink;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixCommand extends Command
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var Symlink
     */
    private $symlink;

    public function __construct(
        StorageInterface $storage,
        Symlink $symlink,
        $name = null
    ) {
        parent::__construct($name);
        $this->storage = $storage;
        $this->symlink = $symlink;
    }

    protected function configure()
    {
        $this->setName('fix');
        $this->setDescription('Remove and reset the symlinks on the correct places');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->path = $_SERVER['HOME'] . '/.composer/vendor/bin/';

        foreach ($this->storage->all() as $name => $content) {
            $this->symlink->remove($name);
            $output->writeln('<fg=cyan>Removed the symlink for ' . $name . '</>');

            $this->symlink->create($name, $content['type']);
            $output->writeln('<info>Created the symlink for ' . $name . '</info>');
        }
    }
}