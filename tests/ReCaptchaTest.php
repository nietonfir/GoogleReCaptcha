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
        $this->responseFactory = $this->getMockBuilder(Api\ResponseFactory::class)
            ->getMock();
    }

    public function testInstantation()
    {
        $client = $this->getClientMock(array(
            new Response(200)
        ));

        $reCaptcha = new ReCaptcha($client, $this->responseFactory);

        $this->assertInstanceOf(
            ReCaptchaInterface::class,
            $reCaptcha
        );
        $this->assertEmpty($reCaptcha->getResponse());
    }

    public function testAPIResponse()
    {
        $json = '{"success":false,"error-codes":["invalid-input-secret"]}';

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
            Api\ResponseInterface::class,
            $response
        );
        $this->assertEquals($responseMock, $response);
        $this->assertFalse($response->isValid());

        $errors = $response->getErrors();

        $this->assertInternalType('array', $errors);
        $this->assertCount(1, $errors);
        $this->assertEquals('invalid-input-secret', $errors[0]);
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
