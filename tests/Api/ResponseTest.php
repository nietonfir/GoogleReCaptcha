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
        $apiResponse = '{"success":false,"challenge_ts":"2017-04-17T00:18:56Z","hostname":"localhost","error-codes":[]}';

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

    public function testDefaultValues()
    {
        $response = new Response();

        $this->assertInstanceOf(
            '\Nietonfir\Google\ReCaptcha\Api\ResponseInterface',
            $response
        );
        $this->assertFalse($response->isValid());
        $this->assertEmpty($response->getChallengeTime());
        $this->assertEmpty($response->getHostname());
        $this->assertEmpty($response->getErrors());
    }

    public function testObjectEqualityInVerify()
    {
        $apiResponse = '{"success":false,"challenge_ts":"2017-04-17T00:18:56Z","hostname":"localhost"}';

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
     * @expectedExceptionMessage The following attributes are missing from the API response: "success", "challenge_ts", "hostname"
     */
    public function testInvalidInstantiationWithMissingSuccess()
    {
        $apiResponse = '{"foo":"bar","error-codes":[]}';

        new Response($apiResponse);
    }

    /**
     * @expectedException \Exception
     */
    public function testInvalidChallengeTime()
    {
        $apiResponse = '{"success":true,"challenge_ts":"foobar","hostname":"localhost"}';

        new Response($apiResponse);
    }

    public function testInvalidHostname()
    {
        $apiResponse = '{"success":true,"challenge_ts":"2017-04-17T00:18:56Z","hostname":"false"}';

        $response = new Response($apiResponse);

        $this->assertTrue($response->isValid());
        $this->assertEquals('false', $response->getHostname());
        $this->assertEmpty($response->getErrors());
    }

    public function testMissingErrorCodes()
    {
        $apiResponse = '{"success":false,"challenge_ts":"2017-04-17T00:18:56Z","hostname":"localhost"}';

        $response = new Response($apiResponse);

        $this->assertFalse($response->isValid());
        $this->assertEmpty($response->getErrors());
    }

    public function testValidResponse()
    {
        $apiResponse = '{"success":true,"challenge_ts":"2017-04-17T00:18:56Z","hostname":"localhost"}';

        $response = new Response($apiResponse);

        $this->assertTrue($response->isValid());
        $this->assertNotEmpty($response->getChallengeTime());

        $challengeTime = $response->getChallengeTime();
        $this->assertEquals(date_default_timezone_get(), $challengeTime->getTimezone()->getName());
        if (version_compare(PHP_VERSION, '5.5', '>=')) {
            $this->assertInstanceOf(\DateTimeImmutable::class, $challengeTime);
        } else {
            $this->assertInstanceOf('\DateTime', $challengeTime);
        }

        $origTime = new \DateTime('2017-04-17T00:18:56Z');
        $this->assertEquals($origTime->getTimestamp(), $challengeTime->getTimestamp());

        $this->assertNotEmpty($response->getHostname());
        $this->assertEquals('localhost', $response->getHostname());

        $this->assertEmpty($response->getErrors());
    }

    public function testInvalidResponse()
    {
        $apiResponse = '{"success":false,"challenge_ts":"2017-04-17T00:18:56Z","hostname":"localhost","error-codes":["missing-input-secret","missing-input-response"]}';

        $response = new Response($apiResponse);
        $this->assertFalse($response->isValid());

        $this->assertNotEmpty($response->getChallengeTime());

        $challengeTime = $response->getChallengeTime();
        $this->assertEquals(date_default_timezone_get(), $challengeTime->getTimezone()->getName());
        if (version_compare(PHP_VERSION, '5.5', '>=')) {
            $this->assertInstanceOf(\DateTimeImmutable::class, $challengeTime);
        } else {
            $this->assertInstanceOf('\DateTime', $challengeTime);
        }

        $origTime = new \DateTime('2017-04-17T00:18:56Z');
        $this->assertEquals($origTime->getTimestamp(), $challengeTime->getTimestamp());

        $this->assertNotEmpty($response->getHostname());
        $this->assertEquals('localhost', $response->getHostname());

        $errors = $response->getErrors();
        $this->assertTrue(is_array($errors));
        $this->assertCount(2, $errors);
        $this->assertEquals(
            array('missing-input-secret', 'missing-input-response'),
            $errors
        );
    }
}
