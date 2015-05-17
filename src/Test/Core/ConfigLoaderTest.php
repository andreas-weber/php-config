<?php

/*
 * This file is part of the andreas-weber/php-config library.
 *
 * (c) Andreas Weber <code@andreas-weber.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace AndreasWeber\Config\Test\Core;

use AndreasWeber\Config\Core\Config;
use AndreasWeber\Config\Core\ConfigLoader;

class ConfigLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConfigLoader Config loader instance
     */
    private $configLoader;

    public function setUp()
    {
        parent::setUp();

        $this->configLoader = new ConfigLoader();
    }

    public function testLoadConfigFile()
    {
        $config = $this->configLoader->load(
            array(
                __DIR__ . '/Fixtures/config1.yml'
            )
        );

        $this->assertEquals(
            array(
                'version' => 'dev',
                'user' => array(
                    'salt' => 'this_is_a_prett_cool_application_salt'
                )
            ),
            $config->toArray()
        );
    }

    public function testLoadConfigFileFromArray()
    {
        $options = array(
            'custom1' => array(
                'hello',
                'you'
            ),
            'custom2' => true
        );

        $config = $this->configLoader->loadArray($options);

        $this->assertEquals(
            $options,
            $config->toArray()
        );
    }

    public function testLoadConfigFileDoesReplacements()
    {
        $this->configLoader->setReplacements(
            array(
                'logPath' => '/some/path'
            )
        );

        $config = $this->configLoader->loadArray(
            array(
                'custom1' => array(
                    'logPath' => '%logPath%/logfile.txt'
                ),
                'custom2' => true
            )
        );

        $this->assertEquals(
            array(
                'custom1' => array(
                    'logPath' => '/some/path/logfile.txt'
                ),
                'custom2' => true
            ),
            $config->toArray()
        );
    }

    public function testLoadMultipleConfigFiles()
    {
        $config = $this->configLoader->load(
            array(
                __DIR__ . '/Fixtures/config1.yml',
                __DIR__ . '/Fixtures/config2.yml'
            )
        );

        $this->assertEquals(
            array(
                'version' => 'dev',
                'user' => array(
                    'salt' => 'this_is_a_prett_cool_application_salt'
                ),
                'logging' => array(
                    'logfile' => '/var/log/application.log'
                )
            ),
            $config->toArray()
        );
    }

    public function testLoadMultipleConfigFilesWithOverride()
    {
        $config = $this->configLoader->load(
            array(
                __DIR__ . '/Fixtures/config1.yml',
                __DIR__ . '/Fixtures/config3.yml'
            )
        );

        $this->assertEquals(
            array(
                'version' => 'prod',
                'user' => array(
                    'salt' => 'this_is_a_prett_cool_application_salt2'
                )
            ),
            $config->toArray()
        );
    }

    public function testLoadConfigFileWithReplacements()
    {
        $this->configLoader->setReplacements(
            array(
                'logPath' => '/some/path'
            )
        );

        $config = $this->configLoader->load(
            array(
                __DIR__ . '/Fixtures/config4.yml'
            )
        );

        $this->assertEquals(
            array(
                'version' => 'dev',
                'user' => array(
                    'salt' => 'this_is_a_prett_cool_application_salt'
                ),
                'logging' => array(
                    'logfile' => '/some/path/application.log'
                )
            ),
            $config->toArray()
        );
    }

    public function testMergeInPassedConfigInstance()
    {
        $config = new Config(
            array(
                'hello' => 'you'
            )
        );

        $config = $this->configLoader->load(
            array(
                __DIR__ . '/Fixtures/config1.yml'
            ),
            $config
        );

        $this->assertEquals(
            array(
                'version' => 'dev',
                'user' => array(
                    'salt' => 'this_is_a_prett_cool_application_salt'
                ),
                'hello' => 'you'
            ),
            $config->toArray()
        );
    }

    public function testLoadMultipleFilesWithAtLeastOneRealFilesIsSuccessful()
    {
        $config = $this->configLoader->load(
            array(
                __DIR__ . '/Fixtures/config1.yml',
                __DIR__ . '/some/path/config1.yml',
                __DIR__ . '/Fixtures/config2.yml',
                __DIR__ . '/some/path/config2.yml'
            )
        );

        $this->assertEquals(
            array(
                'version' => 'dev',
                'user' => array(
                    'salt' => 'this_is_a_prett_cool_application_salt'
                ),
                'logging' => array(
                    'logfile' => '/var/log/application.log'
                )
            ),
            $config->toArray()
        );
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Invalid config file list given. At least one config file must exist.
     */
    public function testLoadMultipleNotExistentFilesThrowsException()
    {
        $this->configLoader->load(
            array(
                __DIR__ . '/some/path/config1.yml',
                __DIR__ . '/some/path/config2.yml'
            )
        );
    }
}
