<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nietonfir\Google\ReCaptcha\Api;

/**
 * Defines the interface for validating the API response.
 *
 * @link https://developers.google.com/recaptcha/docs/verify Official API Documentation
 *
 * @author nietonfir <nietonfir@gmail.com>
 */
interface ResponseInterface
{
    /**
     * Decodes the JSON encoded response from the API and assigns the values
     * to the corresponding attributes.
     *
     * @param  string $response
     * @return ResponseInterface
     */
    public function verify($response);

    /**
     * Returns the validation state of the response.
     *
     * @return boolean
     */
    public function isValid();

    /**
     * Returns the errors of the response
     * The following error-codes are currently in use:
     *   Error code              Description
     *   missing-input-secret    The secret parameter is missing.
     *   invalid-input-secret    The secret parameter is invalid or malformed.
     *   missing-input-response  The response parameter is missing.
     *   invalid-input-response  The response parameter is invalid or malformed.
     *
     * @return array
     */
    public function getErrors();
}
