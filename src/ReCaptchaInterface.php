<?php

namespace Nietonfir\Google\ReCaptcha;

use Nietonfir\Google\ReCaptcha\Api\RequestDataInterface;

interface ReCaptchaInterface
{
    /**
     * Perform the reCAPTCHA validation with the supplied request data
     * and return a response object for further processing.
     *
     * @param  RequestDataInterface $requestData
     * @return ResponseInterface
     */
    public function processRequest(RequestDataInterface $requestData);

    /**
     * Just return the provided response object. If the request was already
     * processed it will contain the necessary data from the API.
     *
     * @return ResponseInterface
     */
    public function getResponse();
}
