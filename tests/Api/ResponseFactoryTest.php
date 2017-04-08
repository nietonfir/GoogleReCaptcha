<?php

namespace Nietonfir\Google\ReCaptcha\Api;

use Nietonfir\Google\ReCaptcha\TestCase;

/*
 * Test the response factory
 *
 * @author nietonfir <nietonfir@gmail.com>
 */
class ResponseFactoryTest extends TestCase
{
    public function testResponseCreation()
    {
        $factory = new ResponseFactory();

        $this->assertInstanceOf(
            ResponseFactoryInterface::class,
            $factory
        );

        $response = $factory->createResponse();

        $this->assertInstanceOf(
            ResponseInterface::class,
            $response
        );
    }
}
