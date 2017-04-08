<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nietonfir\Google\ReCaptcha\Api;

/**
 * Defines the interface for factories generating the API response objects.
 *
 * @author nietonfir <nietonfir@gmail.com>
 */
interface ResponseFactoryInterface
{
    /**
     * Creates a ResponseInterface object.
     *
     * @return ResponseInterface
     */
    public function createResponse();
}
