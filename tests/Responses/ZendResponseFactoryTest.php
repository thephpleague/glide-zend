<?php

namespace League\Glide\Responses;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use PHPUnit\Framework\TestCase;

class ZendResponseFactoryTest extends TestCase
{
    public function testCreateInstance()
    {
        $this->assertInstanceOf(
            'League\Glide\Responses\ZendResponseFactory',
            new ZendResponseFactory()
        );
    }

    public function testCreate()
    {
        $cache = new Filesystem(
            new Local(dirname(__DIR__))
        );

        $factory = new ZendResponseFactory();
        $response = $factory->create($cache, 'kayaks.jpg');

        $this->assertInstanceOf('Zend\Diactoros\Response', $response);
        $this->assertEquals('image/jpeg', $response->getHeaderLine('Content-Type'));
        $this->assertEquals('5175', $response->getHeaderLine('Content-Length'));
        $this->assertContains(
            date_create('+1 years')->format('D, d M Y H:i:s') . ' GMT',
            $response->getHeaderLine('Expires')
        );
        $this->assertEquals('max-age=31536000, public', $response->getHeaderLine('Cache-Control'));
    }
}
