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
     * @var string
     */
    private $path;

    public function __construct(
        StorageInterface $storage,
        $name = null
    ) {
        parent::__construct($name);
        $this->storage = $storage;
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
            $this->removeSymlink($name);
            $output->writeln('<fg=cyan>Removed the symlink ' . $this->path . $name . '</>');

            $this->addSymlink($name, $content['type']);
            $output->writeln('<info>Created the symlink ' . $this->path . $name . '</info>');
        }
    }

    private function removeSymlink($name)
    {
        if (!file_exists($this->path . $name) || !is_writable($this->path . $name)) {
            return;
        }

        unlink($this->path . $name);
        clearstatcache();
    }

    private function addSymlink($name, $type)
    {
        if (!is_writable($this->path)) {
            throw new \Exception('Unable to create symlink: Path (' . $this->path . $name . ') is not writable');
        }

        $result = symlink(
            __DIR__ . '/../' . ucfirst($type) . 'Command.php',
            $this->path . $name
        );

        if (!$result) {
            throw new \Exception('Unable to symlink the path (' . $this->path . $name . ')');
        }
    }
}