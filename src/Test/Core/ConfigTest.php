<?php

namespace AndreasWeber\Config\Test\Core;

use AndreasWeber\Config\Core\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array Array representation of config
     */
    private $configArray;

    /**
     * @var Config Config instance
     */
    private $config;

    protected function setUp()
    {
        parent::setUp();

        $this->configArray = array(
            'something' => 'blabla',
            'wicked' => array(
                'really' => array(
                    'this',
                    'is',
                    5
                )
            )
        );

        $this->config = new Config(
            $this->configArray
        );
    }

    public function testConfigImplementsArrayAccessInterface()
    {
        $this->assertInstanceOf(
            '\\ArrayAccess',
            $this->config
        );
    }

    public function testConfigImplementsCountableInterface()
    {
        $this->assertInstanceOf(
            '\\Countable',
            $this->config
        );
    }

    public function testConfigEqualsConfigArray()
    {
        $this->assertEquals(
            $this->configArray,
            $this->config->toArray()
        );
    }

    public function testArrayAccess()
    {
        $this->assertArrayHasKey(
            'something',
            $this->config
        );
    }

    public function testConfigIsCountable()
    {
        $this->assertCount(
            2,
            $this->config
        );
    }

    public function testConfigImplementsIteratorInterface()
    {
        $this->assertInstanceOf(
            '\\Iterator',
            $this->config
        );
    }

    public function testConfigIteratorIsSuccessFullyImplemented()
    {
        $this->assertTrue(
            $this->config->valid()
        );

        $steppedIn = false;
        foreach ($this->config as $key => $value) {
            $steppedIn = true;
        }

        $this->assertTrue($steppedIn);
    }
}
