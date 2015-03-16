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

    public function testGetMethod()
    {
        $this->assertEquals(
            'blabla',
            $this->config->get('something')
        );
    }

    public function testOffsetGet()
    {
        $this->assertEquals(
            'blabla',
            $this->config['something']
        );
    }

    public function testOffsetSet()
    {
        $this->config['woooop'] = 'pow!';

        $this->assertTrue(
            isset($this->config['woooop'])
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Identifier "undefined" is not defined.
     */
    public function testOffsetGetThrowsExceptionWhenOffsetDoesNotExist()
    {
        $this->config['undefined'];
    }

    public function testOffsetUnset()
    {
        unset($this->config['something']);

        $this->assertFalse(
            isset($this->config['something'])
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Identifier "undefined" is not defined.
     */
    public function testOffsetUnsetThrowsExceptionWhenOffsetDoesNotExist()
    {
        unset($this->config['undefined']);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Deep clone not implemented.
     */
    public function testCloningThrowsLogicException()
    {
        $clone = clone $this->config;
    }

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
}
