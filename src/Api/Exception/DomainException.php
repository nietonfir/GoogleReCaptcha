<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nietonfir\Google\ReCaptcha\Api\Exception;

/**
 * Exception class thrown when an argument doesn't match its definition.
 *
 * @author nietonfir <nietonfir@gmail.com>
 */
class DomainException extends \DomainException implements ExceptionInterface
{
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
