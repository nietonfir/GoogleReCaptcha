<?php

namespace Nietonfir\Google\ReCaptcha\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use Nietonfir\Google\ReCaptcha\ReCaptcha;
use Nietonfir\Google\ReCaptcha\Api\RequestData;

/*
 * Main library test
 *
 * @author nietonfir <nietonfir@gmail.com>
 */
class ReCaptchaTest extends TestCase
{
    private $response;

    public function setUp()
    {
        $this->response = $this->getMockBuilder('\Nietonfir\Google\ReCaptcha\Api\Response')
            ->getMock();
    }

    public function testInstantation()
    {
        $client = $this->getClientMock(array(
            new Response(200)
        ));

        $reCaptcha = new ReCaptcha($client, $this->response);

        $this->assertInstanceOf(
            '\Nietonfir\Google\ReCaptcha\ReCaptchaInterface',
            $reCaptcha
        );
        $this->assertEquals($this->response, $reCaptcha->getResponse());
    }

    public function testAPIResponse()
    {
        $this->response->expects($this->once())
            ->method('verify')
            ->with('{"success":false,"error-codes":["invalid-input-secret"]}')
            ->will($this->returnSelf())
        ;

        $client = $this->getClientMock(array(
            new Response(200, array(), Stream::factory(
                '{"success":false,"error-codes":["invalid-input-secret"]}'
            ))
        ));

        $requestData = new RequestData('secret', 'userResponse', '127.0.0.1');

        $reCaptcha = new ReCaptcha($client, $this->response);
        $response = $reCaptcha->processRequest($requestData);

        $this->assertEquals($response, $this->response);
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
