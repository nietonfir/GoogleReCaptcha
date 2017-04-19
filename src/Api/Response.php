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
    protected $success    = false;

    protected $time       = null;

    protected $hostname   = '';

    protected $errorCodes = array();

    /**
     * Either simply creates a new object instance or also decodes the
     * JSON encoded response from the API and assigns the values to the
     * corresponding attributes.
     *
     * @param string|null $response
     */
    public function __construct($response = null)
    {
        if (is_string($response)) {
            $this->verify($response);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function verify($response)
    {
        $data = json_decode($response, true);

        // validity check - $response must be a JSON string
        if ($data === null && json_last_error() !== \JSON_ERROR_NONE) {
            // throw new \LogicException(sprintf(
            throw new InvalidArgumentException(
                sprintf(
                    'Failed to parse API response as JSON string "%s" with the following error: "%s"',
                    $response,
                    self::getLastJsonError()
                ),
                json_last_error()
            );
        }

        // validity check - $response must contain the following attributes:
        // success, challenge_ts, hostname
        $required = array('success', 'challenge_ts', 'hostname');
        $diff = array_diff_key(array_flip($required), $data);
        $missingNo = count($diff);
        if (0 < $missingNo) {
            throw new DomainException(sprintf(
                'The following %s missing from the API response: "%s"',
                $missingNo == 1 ? 'attribute is' : 'attributes are',
                join('", "', array_flip(array_intersect_key(array_flip($required), $diff)))
            ));
        }

        $this->success = $data['success'];

        if (class_exists('\DateTimeImmutable')) {
            $this->time = new \DateTimeImmutable($data['challenge_ts']);
        } else {
            $this->time = new \DateTime($data['challenge_ts']);
        }

        $this->time = $this->time->setTimezone(new \DateTimeZone(date_default_timezone_get()));

        $this->hostname = $data['hostname'];

        if (isset($data['error-codes'])) {
            $this->errorCodes = $data['error-codes'];
        }

        return $this;
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
    public function getChallengeTime()
    {
        return $this->time;
    }

    /**
     * {@inheritdoc}
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrors()
    {
        return $this->errorCodes;
    }

    /**
     * @return string The last error message created by {@link json_decode()}
     *
     * @link http://php.net/manual/en/function.json-last-error-msg.php#113243
     */
    private static function getLastJsonError()
    {
        if (function_exists('json_last_error_msg')) {
            return json_last_error_msg();
        }
        static $errors = array(
            JSON_ERROR_NONE           => null,
            JSON_ERROR_DEPTH          => 'Maximum stack depth exceeded',
            JSON_ERROR_STATE_MISMATCH => 'Underflow or the modes mismatch',
            JSON_ERROR_CTRL_CHAR      => 'Unexpected control character found',
            JSON_ERROR_SYNTAX         => 'Syntax error, malformed JSON',
            JSON_ERROR_UTF8           => 'Malformed UTF-8 characters, possibly incorrectly encoded',
        );
        $error = json_last_error();

        return array_key_exists($error, $errors)
            ? $errors[$error]
            : sprintf('Unknown error (%s)', $error);
    }
}
