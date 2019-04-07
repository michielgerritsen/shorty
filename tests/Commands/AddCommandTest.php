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

namespace ControlAltDelete\Shorty\tests;

use function ControlAltDelete\Shorty\app;
use ControlAltDelete\Shorty\Contracts\StorageInterface;
use ControlAltDelete\Shorty\Dictionary;
use ControlAltDelete\Shorty\Service\Dependencies;
use ControlAltDelete\Shorty\Service\Symlink;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use ControlAltDelete\Shorty\Commands\AddCommand;

class AddCommandTest extends TestCase
{
    public function testCreatesTheSymlink()
    {
        $storage = $this->getStorageMock();
        $storage->method('has')->willReturn(false);

        $symlink = $this->getSymlinkMock();
        $symlink->method('create')->willReturn(true);

        $dependencies = $this->getDependenciesMock();
        $dependencies->method('check')->willReturn(true);

        $instance = app()->make(AddCommand::class, [
            'storage' => $storage,
            'symlink' => $symlink,
            'dependencies' => $dependencies,
        ]);

        $tester = new CommandTester($instance);
        $tester->execute(['name' => 'fakecommand', 'execute' => 'fakecommand']);

        $this->assertEquals(
            sprintf(Dictionary::COMMAND_ADDED, 'fakecommand') . PHP_EOL,
            $tester->getDisplay()
        );
    }

    public function testGivesAnErrorWhenTheCommandAlreadyExists()
    {
        $storage = $this->getStorageMock();
        $storage->method('has')->willReturn(true);

        $dependencies = $this->getDependenciesMock();
        $dependencies->method('check')->willReturn(true);

        $instance = app()->make(AddCommand::class, [
            'storage' => $storage,
            'dependencies' => $dependencies,
        ]);

        $tester = new CommandTester($instance);
        $tester->execute(['name' => 'fakecommand', 'execute' => 'fakecommand']);

        $this->assertEquals(
            sprintf(Dictionary::COMMAND_ALREADY_EXISTS, 'fakecommand') . PHP_EOL,
            $tester->getDisplay()
        );
    }

    public function testGivesAndErrorWhenDependenciesAreNotMet()
    {
        $dependencies = $this->getDependenciesMock();
        $dependencies->method('check')->willReturn(false);

        $instance = app()->make(AddCommand::class, [
            'dependencies' => $dependencies
        ]);

        $tester = new CommandTester($instance);
        $tester->execute(['name' => 'fakecommand', 'execute' => 'fakecommand']);

        $this->assertEquals(
            Dictionary::COMPOSER_NOT_FOUND,
            trim($tester->getDisplay())
        );
    }

    private function getStorageMock()
    {
        return $this->createMock(StorageInterface::class);
    }

    private function getSymlinkMock()
    {
        return $this->createMock(Symlink::class);
    }

    private function getDependenciesMock()
    {
        return $this->createMock(Dependencies::class);
    }
}