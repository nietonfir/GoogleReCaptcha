<?php

namespace Nietonfir\Google\ReCaptcha\Api;

use Nietonfir\Google\ReCaptcha\Tests;

/*
 * Test the response factory
 *
 * @author nietonfir <nietonfir@gmail.com>
 */
class ResponseFactoryTest extends Tests\TestCase
{
    public function testResponseCreation()
    {
        $factory = new ResponseFactory();

        $this->assertInstanceOf(
            '\Nietonfir\Google\ReCaptcha\Api\ResponseFactoryInterface',
            $factory
        );

        $response = $factory->createResponse();

        $this->assertInstanceOf(
            '\Nietonfir\Google\ReCaptcha\Api\ResponseInterface',
            $response
        );
    }
}
