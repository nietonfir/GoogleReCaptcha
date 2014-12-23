<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nietonfir\Google\ReCaptcha\Api;

/**
 * A value object containing the values of the required attributes for a
 * valid reCAPTCHA API request.
 *
 * @link https://developers.google.com/recaptcha/docs/verify Official API Documentation
 *
 * @author nietonfir <nietonfir@gmail.com>
 */
class RequestData implements RequestDataInterface
{
    private $secret;

    private $userResponse;

    private $remoteIP;

    /**
     * Instantiate the object with the required values.
     *
     * @param string $secret       The shared reCAPTCHA key
     * @param string $userResponse The user response token provided by the reCAPTCHA to the user
     * @param string $remoteIP     The user's IP address (optional)
     */
    public function __construct($secret, $userResponse, $remoteIP = '')
    {
        $this->secret = $secret;
        $this->userResponse = $userResponse;
        $this->remoteIP = $remoteIP;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return array(
            'secret'   => $this->secret,
            'response' => $this->userResponse,
            'remoteip' => $this->remoteIP
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getAsFormattedString()
    {
        return sprintf('secret=%s&response=%s&remoteip=%s',
            $this->secret,
            $this->userResponse,
            $this->remoteIP
        );
    }
}
