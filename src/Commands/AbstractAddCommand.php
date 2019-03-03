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
     * @var string
     */
    protected $type = '';

    public function __construct(
        StorageInterface $storage,
        SymlinkInterface $symlink,
        $name = null
    ) {
        parent::__construct($name);

        $this->storage = $storage;
        $this->symlink = $symlink;
    }

    protected function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Which file should be the base for this command?');
        $this->addArgument('execute', InputArgument::REQUIRED, 'Which command should be shortied?');
        $this->addOption('interpreter', null, InputOption::VALUE_OPTIONAL, 'Which interpreter should be used to execute this command?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        if (!$this->checkDependencies()) {
            return;
        }

        $name = $input->getArgument('name');
        if ($this->storage->has($name)) {
            $this->output->writeln(
                '<error>The command ' . $name . ' already exists. Use `shorty fix` to recreate it</error>'
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

        $this->output->writeln('<info>Command ' . $name . ' added. Try it out!</info>');
    }

    private function getPath()
    {
        return getcwd();
    }

    private function checkDependencies()
    {
        $path = $_SERVER['PATH'];
        $parts = array_filter(explode(':', $path), function ($part) {
            return strpos($part, '.composer/vendor/bin') !== false;
        });

        if (empty($parts)) {
            $this->output->writeln('<error>The .composer/vendor/bin bin is not in your $PATH. Please add it.</error>');
            return false;
        }

        return true;
    }
}