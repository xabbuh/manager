<?php

/*
 * This file is part of the Puli PackageManager package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Puli\PackageManager\Tests\Config;

use Puli\PackageManager\Config\GlobalConfig;
use Puli\PackageManager\Config\GlobalConfigStorage;
use Puli\PackageManager\Config\Reader\GlobalConfigReaderInterface;
use Puli\PackageManager\Config\Writer\GlobalConfigWriterInterface;
use Puli\PackageManager\FileNotFoundException;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class GlobalConfigStorageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GlobalConfigStorage
     */
    private $storage;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|GlobalConfigReaderInterface
     */
    private $reader;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|GlobalConfigWriterInterface
     */
    private $writer;

    protected function setUp()
    {
        $this->reader = $this->getMock('Puli\PackageManager\Config\Reader\GlobalConfigReaderInterface');
        $this->writer = $this->getMock('Puli\PackageManager\Config\Writer\GlobalConfigWriterInterface');

        $this->storage = new GlobalConfigStorage($this->reader, $this->writer);
    }

    public function testLoadGlobalConfig()
    {
        $config = new GlobalConfig();

        $this->reader->expects($this->once())
            ->method('readGlobalConfig')
            ->with('/path')
            ->will($this->returnValue($config));

        $this->assertSame($config, $this->storage->loadGlobalConfig('/path'));
    }

    public function testLoadGlobalConfigCreatesNewIfNotFound()
    {
        $this->reader->expects($this->once())
            ->method('readGlobalConfig')
            ->with('/path')
            ->will($this->throwException(new FileNotFoundException()));

        $this->assertEquals(new GlobalConfig('/path'), $this->storage->loadGlobalConfig('/path'));
    }

    public function testSaveGlobalConfig()
    {
        $config = new GlobalConfig('/path');

        $this->writer->expects($this->once())
            ->method('writeGlobalConfig')
            ->with($config, '/path');

        $this->storage->saveGlobalConfig($config);
    }
}