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
use ControlAltDelete\Shorty\Contracts\SymlinkInterface;
use ControlAltDelete\Shorty\Dictionary;
use ControlAltDelete\Shorty\Service\Dependencies;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AbstractAddCommand extends Command
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var SymlinkInterface
     */
    private $symlink;

    /**
     * @var Dependencies
     */
    private $dependencies;

    /**
     * @var string
     */
    protected $type = '';

    public function __construct(
        Dependencies $dependencies,
        StorageInterface $storage,
        SymlinkInterface $symlink,
        $name = null
    ) {
        parent::__construct($name);

        $this->storage = $storage;
        $this->symlink = $symlink;
        $this->dependencies = $dependencies;
    }

    protected function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Which file should be the base for this command?');
        $this->addArgument('execute', InputArgument::REQUIRED, 'Which command should be shortend?');
        $this->addOption('interpreter', null, InputOption::VALUE_OPTIONAL, 'Which interpreter should be used to execute this command?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        if (!$this->dependencies->check()) {
            $this->output->writeln('<error>' . Dictionary::COMPOSER_NOT_FOUND . '</error>');
            return;
        }

        $name = $input->getArgument('name');
        if ($this->storage->has($name)) {
            $this->output->writeln(
                '<error>' . sprintf(Dictionary::COMMAND_ALREADY_EXISTS, $name) . '</error>'
            );
            return;
        }

        $this->storage->set($name, [
            'path' => $this->getPath(),
            'command' => $input->getArgument('execute'),
            'type' => $this->type,
            'interpreter' => $input->getOption('interpreter'),
        ]);

        $this->symlink->create($name, $this->type);

        $this->output->writeln('<info>' . sprintf(Dictionary::COMMAND_ADDED, $name) . '</info>');
    }

    private function getPath()
    {
        return getcwd();
    }
}
