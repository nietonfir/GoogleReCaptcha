<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nietonfir\Google\ReCaptcha\Api;

use Nietonfir\Google\ReCaptcha\Api\Exception\DomainException,
    Nietonfir\Google\ReCaptcha\Api\Exception\InvalidArgumentException;

/**
 * A helper class for simplifying the access to the return response from the
 * noCAPTCHA API.
 *
 * @link https://developers.google.com/recaptcha/docs/verify Official API Documentation
 *
 * @author nietonfir <nietonfir@gmail.com>
 */
class Response implements ResponseInterface
{
    private $success;

    private $errorCodes;

    /**
     * Decodes the JSON encoded response from the API and assigns the values
     * to the corresponding attributes.
     *
     * @param string $response
     */
    public function __construct($response)
    {
        // TODO - check that the data IS a json string
        // TODO - and that it has at least the success attribute!

        $data = json_decode($response, true);

        // validity check - $response must be a JSON string
        if ($data === null && json_last_error() !== \JSON_ERROR_NONE) {
            // throw new \LogicException(sprintf(
            throw new InvalidArgumentException(
                sprintf(
                    'Failed to parse API response as JSON string "%s" with the following error: "%s"',
                    $response,
                    json_last_error_msg()
                ),
                json_last_error()
            );
        }

        // validity check - $response must contain a 'success' attribute
        if (!isset($data['success'])) {
            throw new DomainException(sprintf(
                'API Response has no success attribute: "%s"',
                $response
            ));
        }

        $this->success = $data['success'];
        $this->errorCodes = (isset($data['error-codes']))
            ? $data['error-codes']
            : array()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        return $this->success;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrors()
    {
        return $this->errorCodes;
    }
}
