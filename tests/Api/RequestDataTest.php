<?php

namespace Nietonfir\Google\ReCaptcha\Api;

use Nietonfir\Google\ReCaptcha\TestCase;

/*
 * Test the RequestData value object
 *
 * @author nietonfir <nietonfir@gmail.com>
 */
class RequestDataTest extends TestCase
{
    public function testInstantation()
    {
        $requestData = new RequestData('foo', 'bar', 'baz');

        $this->assertInstanceOf(
            RequestDataInterface::class,
            $requestData
        );
    }

    public function testReturnValues()
    {
        $requestData = new RequestData('foo', 'bar', 'baz');

        // check that an array is returned with all necessary keys
        $postRequest = $requestData->getValue();
        $this->assertTrue(is_array($postRequest));
        $this->assertCount(3, $postRequest);
        $this->assertNotEmpty($postRequest);
        $this->assertArrayHasKey('secret', $postRequest);
        $this->assertArrayHasKey('response', $postRequest);
        $this->assertArrayHasKey('remoteip', $postRequest);
        $this->assertEquals(
            array('secret' => 'foo', 'response' => 'bar', 'remoteip' => 'baz'),
            $postRequest
        );

        // is the expected formatted string returned
        $getRequest = $requestData->getAsFormattedString();
        $this->assertTrue(is_string($getRequest));
        $this->assertNotEmpty($getRequest);
        $this->assertEquals('secret=foo&response=bar&remoteip=baz', $getRequest);
    }
}
