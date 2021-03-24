<?php

namespace Responses;

use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Glide\Responses\ZendResponseFactory;
use PHPUnit\Framework\TestCase;

class ZendResponseFactoryTest extends TestCase
{
    public function testCreateInstance()
    {
        self::assertInstanceOf(
            'League\Glide\Responses\ZendResponseFactory',
            new ZendResponseFactory()
        );
    }

    public function testCreate()
    {
        $cache = new Filesystem(
            new LocalFilesystemAdapter(dirname(__DIR__))
        );

        $factory = new ZendResponseFactory();
        $response = $factory->create($cache, 'kayaks.jpg');

        self::assertInstanceOf('Zend\Diactoros\Response', $response);
        self::assertEquals('image/jpeg', $response->getHeaderLine('Content-Type'));
        self::assertEquals('5175', $response->getHeaderLine('Content-Length'));
        self::assertStringContainsString(gmdate('D, d M Y H:i', strtotime('+1 years')), $response->getHeaderLine('Expires'));
        self::assertEquals('max-age=31536000, public', $response->getHeaderLine('Cache-Control'));
    }
}
