<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nietonfir\Google\ReCaptcha\Api;

/**
 * A factory for generating response objects containing the response from the
 * noCAPTCHA API.
 *
 * @link https://developers.google.com/recaptcha/docs/verify Official API Documentation
 *
 * @author nietonfir <nietonfir@gmail.com>
 */
class ResponseFactory implements ResponseFactoryInterface
{
    public function createResponse()
    {
        return new Response();
    }
}
