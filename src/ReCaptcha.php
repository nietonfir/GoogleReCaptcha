<?php

namespace Nietonfir\Google\ReCaptcha;

use GuzzleHttp\ClientInterface;
use Nietonfir\Google\ReCaptcha\Api\RequestDataInterface,
    Nietonfir\Google\ReCaptcha\Api\ResponseFactoryInterface;

class ReCaptcha implements ReCaptchaInterface
{
    const API_URL = 'https://www.google.com/recaptcha/api/siteverify';

    private $client;

    private $factory;

    private $lastResponse;

    public function __construct(ClientInterface $client, ResponseFactoryInterface $factory)
    {
        $this->client  = $client;
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function processRequest(RequestDataInterface $requestData)
    {
        $url = ($this->client->getBaseUrl() == '')
            ? self::API_URL
            : null;

        $data = $this->client->get($url, array(
            'query' => $requestData->getValue()
        ));

        $response = $this->factory->createResponse();
        $this->lastResponse = $response->verify($data->getBody());

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        if ($this->lastResponse) {
            @trigger_error('Using getResponse is deprecated since 0.1.0 and will be removed in 1.0.0. Until then it will return the last response available.', E_USER_DEPRECATED);
            return $this->lastResponse;
        }
    }
}
