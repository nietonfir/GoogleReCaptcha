<?php

namespace Nietonfir\Google\ReCaptcha\Api;

use Nietonfir\Google\ReCaptcha\TestCase;

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
            ResponseInterface::class,
            $response
        );
    }

    public function testEmptyInstantiation()
    {
        $response = new Response();

        $this->assertInstanceOf(
            ResponseInterface::class,
            $response
        );
    }

    public function testObjectEqualityInVerify()
    {
        $apiResponse = '{"success":false}';

        $response = new Response();
        $rsp = $response->verify($apiResponse);
        $this->assertEquals($response, $rsp);
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
