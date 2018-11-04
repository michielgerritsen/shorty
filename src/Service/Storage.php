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

use ControlAltDelete\Shorty\Contracts\StorageInterface;

class Storage implements StorageInterface
{
    const PATH = '../../storage';
    const NAME = 'config.json';

    /**
     * @var array
     */
    private $config = null;

    /**
     * @param string $name
     * @param array $contents
     * @return bool
     */
    public function set(string $name, array $contents): bool
    {
        $this->load();

        $this->config[$name] = $contents;

        $this->persist();

        return true;
    }

    /**
     * @param $name
     * @return string
     */
    public function get(string $name): array
    {
        $this->load();

        if (!array_key_exists($name, $this->config)) {
            return [];
        }

        return $this->config[$name];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        $this->load();

        return array_key_exists($name, $this->config);
    }

    public function delete(string $name): bool
    {
        if (!$this->has($name)) {
            return false;
        }

        unset($this->config[$name]);

        $this->persist();

        return true;
    }

    private function ensureConfigFileExists()
    {
        $path = $this->getPath() . '/' . static::NAME;
        if (file_exists($path)) {
            return;
        }

        if (!is_writable($this->getPath())) {
            throw new \Exception('Unable to save the config file');
        }

        touch($path);
    }

    private function load()
    {
        if ($this->config !== null) {
            return;
        }

        $this->ensureConfigFileExists();

        $contents = file_get_contents($this->getPathWithFilename());
        $this->config = json_decode($contents, JSON_OBJECT_AS_ARRAY) ?? [];
    }

    /**
     * @return string
     */
    private function getPath(): string
    {
        return __DIR__ . '/' . static::PATH;
    }

    /**
     * @return string
     */
    private function getPathWithFilename(): string
    {
        return $this->getPath() . '/' . static::NAME;
    }

    private function persist()
    {
        file_put_contents(
            $this->getPathWithFilename(),
            json_encode($this->config, JSON_PRETTY_PRINT)
        );
    }
}