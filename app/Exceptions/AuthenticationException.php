<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class AuthenticationException extends Exception
{
    /**
     * AuthenticationException constructor.
     *
     * @param string         $message
     * @param integer        $code
     * @param null|Throwable $previous
     */
    public function __construct(string     $message = 'Invalid credentials.',
                                int        $code = 0,
                                ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
