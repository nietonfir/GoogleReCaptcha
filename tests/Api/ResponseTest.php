<?php

namespace Nietonfir\Google\ReCaptcha\Tests\Api;

use Nietonfir\Google\ReCaptcha\Tests\TestCase;
use Nietonfir\Google\ReCaptcha\Api\Response;

/*
 * Test the API response helper class
 *
 * @author nietonfir <nietonfir@gmail.com>
 */
class ResponseTest extends TestCase
{
    public function testAPIInstantiation()
    {
        $apiResponse = '{"success":false,"error-codes":[]}';

        $response = new Response($apiResponse);

        $this->assertInstanceOf(
            '\Nietonfir\Google\ReCaptcha\Api\ResponseInterface',
            $response
        );
    }

    public function testEmptyInstantiation()
    {
        $response = new Response();

        $this->assertInstanceOf(
            '\Nietonfir\Google\ReCaptcha\Api\ResponseInterface',
            $response
        );
    }

    /**
     * @expectedException \Nietonfir\Google\ReCaptcha\Api\Exception\InvalidArgumentException
     */
    public function testInvalidInstantiationWithNoJSONResponse()
    {
        new Response('i\'m not a JSON string');
    }

    /**
     * @expectedException \Nietonfir\Google\ReCaptcha\Api\Exception\DomainException
     */
    public function testInvalidInstantiationWithMissingSuccess()
    {
        $apiResponse = '{"foo":"bar","error-codes":[]}';

        $response = new Response($apiResponse);
    }

    public function testMissingErrorCodes()
    {
        $apiResponse = '{"success":false}';

        $response = new Response($apiResponse);

        $this->assertFalse($response->isValid());
        $this->assertEmpty($response->getErrors());
    }

    public function testValidResponse()
    {
        $apiResponse = '{"success":true}';

        $response = new Response($apiResponse);

        $this->assertTrue($response->isValid());
        $this->assertEmpty($response->getErrors());
    }

    public function testInvalidResponse()
    {
        $apiResponse = '{"success":false,"error-codes":["missing-input-secret","missing-input-response"]}';

        $response = new Response($apiResponse);
        $this->assertFalse($response->isValid());

        $errors = $response->getErrors();
        $this->assertTrue(is_array($errors));
        $this->assertCount(2, $errors);
        $this->assertEquals(
            array('missing-input-secret', 'missing-input-response'),
            $errors
        );
    }
}
