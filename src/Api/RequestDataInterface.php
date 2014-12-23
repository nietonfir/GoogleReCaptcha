<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nietonfir\Google\ReCaptcha\Api;

/**
 * Define the interface for the request data, so that a valid reCAPTCHA API
 * request can be performed with it.
 * It basically defines two functions, so that both GET and POST requests could
 * (theoretically) be performed.
 *
 * @link https://developers.google.com/recaptcha/docs/verify Official API Documentation
 *
 * @author nietonfir <nietonfir@gmail.com>
 */
interface RequestDataInterface
{
    /**
     * Simple getter to retrieve the stored request data as an array.
     *
     * @return array The stored request data
     */
    public function getValue();

    /**
     * Build a properly formatted string for a valid reCAPTCHA API request
     *
     * @return string The stored request data as formatted string
     */
    public function getAsFormattedString();
}
