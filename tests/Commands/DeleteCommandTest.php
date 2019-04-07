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
use ControlAltDelete\Shorty\Commands\DeleteCommand;
use ControlAltDelete\Shorty\Contracts\StorageInterface;
use ControlAltDelete\Shorty\Dictionary;
use ControlAltDelete\Shorty\Service\Symlink;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class DeleteCommandTest extends TestCase
{
    public function testDeletesTheSymlink()
    {
        $storage = $this->getStorageMock();
        $symlink = $this->getSymlinkMock();
        $symlink->expects($this->once())->method('remove');

        $instance = app()->make(DeleteCommand::class, [
            'storage' => $storage,
            'symlink' => $symlink,
        ]);

        $tester = new CommandTester($instance);
        $tester->execute(['name' => 'fakecommand']);

        $this->assertEquals(
            sprintf(Dictionary::COMMAND_REMOVED, 'fakecommand'),
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
}
