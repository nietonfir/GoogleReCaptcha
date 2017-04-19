<?php

namespace Nietonfir\Google\ReCaptcha;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use Nietonfir\Google\ReCaptcha\Api;

/*
 * Main library test
 *
 * @author nietonfir <nietonfir@gmail.com>
 */
class ReCaptchaTest extends TestCase
{
    private $responseFactory;

    public function setUp()
    {
        $this->responseFactory = $this->getMockBuilder('\Nietonfir\Google\ReCaptcha\Api\ResponseFactory')
            ->getMock();
    }

    public function testApiUrlValue()
    {
        $clazz = new \ReflectionClass('\Nietonfir\Google\ReCaptcha\ReCaptcha');
        $constants = $clazz->getConstants();

        $this->assertNotEmpty($constants);
        $this->assertCount(1, $constants);
        $this->assertEquals('API_URL', key($constants));

        $this->assertEquals('https://www.google.com/recaptcha/api/siteverify', ReCaptcha::API_URL);
    }

    /**
     * @group legacy
     */
    public function testInstantation()
    {
        $client = $this->getClientMock(array(
            new Response(200)
        ));

        $reCaptcha = new ReCaptcha($client, $this->responseFactory);

        $this->assertInstanceOf(
            '\Nietonfir\Google\ReCaptcha\ReCaptchaInterface',
            $reCaptcha
        );
        $this->assertEmpty($reCaptcha->getResponse());
    }

    public function testAPIResponse()
    {
        $json = '{"success":false,"challenge_ts":"2017-04-17T00:18:56Z","hostname":"localhost","error-codes":["invalid-input-secret"]}';

        $responseMock = new Api\Response();

        $this->responseFactory->expects($this->once())
            ->method('createResponse')
            ->will($this->returnValue($responseMock))
        ;

        $client = $this->getClientMock(array(
            new Response(200, array(), Stream::factory($json))
        ));

        $requestData = new Api\RequestData('secret', 'userResponse', '127.0.0.1');

        $reCaptcha = new ReCaptcha($client, $this->responseFactory);
        $response = $reCaptcha->processRequest($requestData);

        $this->assertInstanceOf(
            '\Nietonfir\Google\ReCaptcha\Api\ResponseInterface',
            $response
        );
        $this->assertEquals($responseMock, $response);
        $this->assertFalse($response->isValid());

        $errors = $response->getErrors();

        $this->assertInternalType('array', $errors);
        $this->assertCount(1, $errors);
        $this->assertEquals('invalid-input-secret', $errors[0]);

        return $reCaptcha;
    }

    /**
     * @group legacy
     */
    public function testDeprecatedGetResponse()
    {
        $json = '{"success":true,"challenge_ts":"2017-04-17T00:18:56Z","hostname":"localhost"}';

        $responseMock = new Api\Response();

        $this->responseFactory->expects($this->once())
            ->method('createResponse')
            ->will($this->returnValue($responseMock))
        ;

        $client = $this->getClientMock(array(
            new Response(200, array(), Stream::factory($json))
        ));

        $requestData = new Api\RequestData('secret', 'userResponse', '127.0.0.1');

        $reCaptcha = new ReCaptcha($client, $this->responseFactory);

        $this->assertEmpty($reCaptcha->getResponse());

        $response = $reCaptcha->processRequest($requestData);

        $this->assertInstanceOf(
            '\Nietonfir\Google\ReCaptcha\Api\ResponseInterface',
            $response
        );
        $this->assertEquals($responseMock, $response);
        $this->assertTrue($response->isValid());

        return $reCaptcha;
    }

    /**
     * @group legacy
     * @depends testDeprecatedGetResponse
     * @expectedDeprecation Using getResponse is deprecated since 0.1.0 and will be removed in 1.0.0. Until then the last response will be returned.
     */
    public function testDeprecatedGetResponseError($reCaptcha)
    {
        $response = $reCaptcha->getResponse();

        $this->assertInstanceOf(
            '\Nietonfir\Google\ReCaptcha\Api\ResponseInterface',
            $response
        );
        $this->assertTrue($response->isValid());
    }

    private function getClientMock($responses)
    {
        $client = new Client();
        $client->getEmitter()->attach(
            new Mock($responses)
        );

        return $client;
    }
}
