<?php

namespace Nietonfir\Google\ReCaptcha;

use GuzzleHttp\ClientInterface;
use Nietonfir\Google\ReCaptcha\Api\RequestDataInterface,
    Nietonfir\Google\ReCaptcha\Api\ResponseInterface;

class ReCaptcha implements ReCaptchaInterface
{
    const API_URL = 'https://www.google.com/recaptcha/api/siteverify';

    protected $client;

    protected $submitted;

    protected $response;

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
        $url = ($this->client->getBaseUrl() == '')
            ? self::API_URL
            : null;

        $response = $this->client->get($url, array(
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
