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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteCommand extends Command
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

    public function configure()
    {
        $this->setName('delete');
        $this->setDescription('Delete an shortcut command');

        $this->addArgument('name', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        $this->storage->delete($name);
        $this->symlink->remove($name);

        $output->writeln('<info>the [' . $name . '] shortcut is succesfully removed</info>');
    }
}