<?php

namespace AndreasWeber\Config\Core;

use Assert\Assertion;
use Symfony\Component\Yaml\Yaml;

class ConfigLoader
{
    /**
     * @var array Replacements
     */
    private $replacements;

    /**
     * __construct()
     *
     * @param array $replacements Replacements
     */
    public function __construct(array $replacements = null)
    {
        if ($replacements) {
            $this->setReplacements($replacements);
        }
    }

    /**
     * Sets the replacements.
     *
     * @param array $replacements Replacements
     *
     * @return $this
     */
    public function setReplacements(array $replacements)
    {
        $this->replacements = array();

        foreach ($replacements as $property => $value) {
            $this->replacements['%' . $property . '%'] = $value;
        }

        return $this;
    }

    /**
     * Load multiple config files.
     * Each file gets merged into a single config instance.
     *
     * @param array  $files
     * @param Config $config When passed config instance is used to merge into
     *
     * @return Config
     */
    public function load(array $files, Config $config = null)
    {
        if (is_null($config)) {
            $config = new Config();
        }

        if (!$this->isValidFileList($files)) {
            throw new \RuntimeException('Invalid config file list given. At least one config file to parse must exist.');
        }

        foreach ($files as $file) {
            if ($this->isFileExisting($file)) {
                $config->merge(
                    $this->loadFile($file)
                );
            }
        }

        return $config;
    }

    /**
     * Checks if a file exists.
     *
     * @param string $file File
     *
     * @return bool
     */
    private function isFileExisting($file)
    {
        return is_file($file);
    }

    /**
     * Check if a valid file list is given.
     *
     * @param array $files
     *
     * @return bool
     */
    private function isValidFileList(array $files)
    {
        Assertion::notEmpty($files, 'Files array can\'t be empty');

        $valid = false;

        foreach ($files as $file) {
            if ($this->isFileExisting($file)) {
                $valid = true;
            }
        }

        return $valid;
    }

    /**
     * Load a config file.
     *
     * @param string $file
     *
     * @return Config
     */
    private function loadFile($file)
    {
        Assertion::file($file, 'Can\'t parse config file. File does not exist: ' . $file);

        $array = $this->parseConfigFile($file);
        $array = $this->doReplacements($array);

        return new Config($array);
    }

    /**
     * Parses config file and returns array.
     *
     * @param string $file
     *
     * @return array
     */
    private function parseConfigFile($file)
    {
        Assertion::file($file, 'Can\'t parse config file. File does not exist: ' . $file);

        $config = Yaml::parse(file_get_contents($file));

        if (is_null($config)) {
            $config = array();
        }

        return $config;
    }

    /**
     * Replace placeholder with values.
     *
     * @param mixed $value
     *
     * @return array|string
     */
    private function doReplacements($value)
    {
        if (!$this->replacements) {
            return $value;
        }

        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = $this->doReplacements($v);
            }

            return $value;
        }

        if (is_string($value)) {
            return strtr($value, $this->replacements);
        }

        return $value;
    }
}
