<?php

namespace Nietonfir\Google\ReCaptcha;

use GuzzleHttp\ClientInterface;
use Nietonfir\Google\ReCaptcha\Api\RequestDataInterface,
    Nietonfir\Google\ReCaptcha\Api\ResponseInterface;

class ReCaptcha implements ReCaptchaInterface
{
    private $client;

    private $submitted;

    private $response;

    public function __construct(ClientInterface $client, ResponseInterface $response)
    {
        $this->client = $client;
        $this->response = $response;
        $this->submitted = false;
    }

    /**
     * {@inheritdoc}
     */
    public function processRequest(RequestDataInterface $requestData)
    {
        $response = $this->client->get('', array(
            'query' => $requestData->getValue()
        ));

        $this->submitted = true;

        return $this->response->verify($response->getBody());
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        return $this->response;
    }
}
